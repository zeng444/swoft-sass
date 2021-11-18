<?php
use Application\Admin\Components\Db\Criteria;
use Application\Admin\Components\Mvc\Model\Behavior\Log as OperationLog;
use Application\Core\Models\AdministratorRole;
use Application\Core\Models\Administrator;

class AdministratorRoleController extends ControllerBase
{

    /**
     * 管理员角色
     */
    public function indexAction()
    {
        if ($this->request->isAjax()) {
            $page = $this->request->get('page', 'int');
            $page = $page > 0 ? $page : 1;
            $pageSize = $this->request->get('pageSize', 'int');
            $pageSize = $pageSize > 0 ? $pageSize : Criteria::PAGE_SIZE;
            $post = [];
            $loginAdmin = Administrator::findFirst($this->adminInfo->id);
            if ($loginAdmin->isSuperAdminRole() === false) {
                $post['id'] = $loginAdmin->role_id;
            }
            $post = array_merge($_REQUEST, $post);
            $query = Criteria::fromInput($this->di, '\Application\Core\Models\AdministratorRole', $post);
            $parameters = $query->getParams();
            $sortRules = Criteria::getQuerySorting(true);
            $models = AdministratorRole::find(array_merge($parameters, [
                'offset' => ($page - 1) * $pageSize,
                'limit' => $pageSize,
                'order' => $sortRules ? $sortRules : 'id asc'
            ]));
            $data = [];
            foreach ($models as $key => $model) {
                $data[$key] = $model->toArray(['id', 'role_id', 'name', 'nickname', 'is_block', 'is_deleted', 'createdAt', 'updatedAt']);
            }
            $this->apiSuccess([
                'data' => $data,
                'count' => (string)AdministratorRole::count($parameters)
            ]);
        }
    }

    /**
     * 管理员角色添加编辑
     */
    public function postAction()
    {
        if ($this->request->isAjax()) {
            $loginAdmin = Administrator::findFirst($this->adminInfo->id);
            if ($loginAdmin->isSuperAdminRole() === false) {
                $this->apiError('对不起，您不是超级管理员组，没有操作角色的权限');
            }
            if ($this->request->isPost()) {

                $postData = array_map(function ($val) {
                    if (!is_array($val) && trim($val) === '') {
                        $val = null;
                    }
                    return $val;
                }, $_POST);
                if (isset($postData['id']) && $postData['id']) {
                    $model = AdministratorRole::findFirst($postData['id']);
                } else {
                    $model = new AdministratorRole();
                }
                if(!$model){
                    $this->apiError('数据不存在');
                }
                $model->addBehavior(new OperationLog(['id' => $this->adminInfo->id, 'name' => $this->adminInfo->name]));
                $rules = [];
                if (isset($postData['rule']) && $postData['rule']) {
                    foreach ($postData['rule'] as $key => $val) {
                        if ($val === 'true') {
                            array_push($rules, $key);
                        }
                    }
                }

                $model->rule_map = $rules ? json_encode($rules) : null;
                if ($model->save($postData) === false) {
                    $this->apiError($model->getFirstError());
                }
                $this->apiSuccess([
                    'success' => '1'
                ]);
            } else {
                $id = $this->request->get('id', 'int');
                $model = $id ? AdministratorRole::findFirst($id) : new AdministratorRole();
                if (!$model) {
                    $this->apiError('数据不存在');
                }
                $data = $model->toArray(['id', 'role_id', 'name', 'nickname', 'is_block', 'is_deleted', 'createdAt', 'updatedAt']);
                $data['acl'] = AdministratorRole::getAclList(__DIR__);
                //分组acl

                $rules = AdministratorRole::getMyAcl($id);
                $data['rule'] = new stdClass();
                foreach ($rules as $rule) {
                    $data['rule']->$rule = true;
                }
                $this->apiSuccess([
                    'data' => $data
                ]);
            }

        }
    }

    /**
     * 管理员角色删除
     */
    public function deleteAction()
    {
        if ($this->request->isAjax()) {
            $loginAdmin = Administrator::findFirst($this->adminInfo->id);
            if ($loginAdmin->isSuperAdminRole() === false) {
                $this->apiError('对不起，您不是超级管理员组，没有操作角色的权限');
            }
            $ids = trim($this->request->get('id'));
            $ids = explode(',', $ids);
            $success = 0;
            foreach ($ids as $id) {
                $model = AdministratorRole::findFirst($id);
                if ($model) {
                    $model->addBehavior(new OperationLog(['id' => $this->adminInfo->id, 'name' => $this->adminInfo->name]));
                    if ($model->delete() === false) {
                        $this->apiError($model->getFirstError());
                    }
                    $success++;
                }
            }
            $this->apiSuccess([
                'success' => (string)$success
            ]);
        }
    }


}
