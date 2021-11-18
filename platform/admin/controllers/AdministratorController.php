<?php
use Application\Admin\Components\Db\Criteria;
use Application\Admin\Components\Mvc\Model\Behavior\Log as OperationLog;
use Application\Core\Models\Administrator;
use Application\Core\Models\AdministratorRole;

class AdministratorController extends ControllerBase
{

    /**
     * 管理员列表
     *
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
                $post['id'] = $loginAdmin->id;
            }
            $post = array_merge($_REQUEST, $post);
            $query = Criteria::fromInput($this->di, '\Application\Core\Models\Administrator', $post);
            $parameters = $query->getParams();
            $sortRules = Criteria::getQuerySorting(true);
            $models = Administrator::find(array_merge($parameters, [
                'offset' => ($page - 1) * $pageSize,
                'limit' => $pageSize,
                'order' => $sortRules ? $sortRules : 'id asc'
            ]));
            $data = [];
            foreach ($models as $key => $model) {
                $data[$key] = $model->toArray(['id', 'role_id', 'name', 'nickname', 'is_block', 'is_deleted', 'createdAt', 'updatedAt']);
                $administratorRole = $model->getAdministratorRole();
                $data[$key]['role_name'] = ($model->role_id && $administratorRole) ? $administratorRole->name : '';
                $data[$key]['is_wechat_openid'] = $model->wechat_openid ? '1':'0';
            }
            $this->apiSuccess([
                'data' => $data,
                'count' => (string)Administrator::count($parameters)
            ]);
        }
    }

    /**
     * 添加管理管理员
     *
     */
    public function postAction()
    {
        if ($this->request->isAjax()) {
            if ($this->request->isPost()) {
                $postData = array_map(function ($val) {
                    if (trim($val) === '') {
                        $val = null;
                    }
                    return $val;
                }, $_POST);
                $loginAdmin = Administrator::findFirst($this->adminInfo->id);
                if (isset($postData['id']) && $postData['id']) {
                    $isCreate = false;
                    $model = Administrator::findFirst($postData['id']);
                } else {
                    $isCreate = true;
                    $model = new Administrator();
                }
                if(!$model){
                    $this->apiError('数据不存在');
                }
                $model->addBehavior(new OperationLog(['id' => $this->adminInfo->id, 'name' => $this->adminInfo->name]));
                $roleId = isset($postData['role_id']) ? $postData['role_id'] : '';
                $isBlock = isset($postData['is_block']) ? $postData['is_block'] : '';
                $name = isset($postData['name']) ? $postData['name'] : '';
                $nickname = isset($postData['nickname']) ? $postData['nickname'] : '';
                $password = isset($postData['password']) ? $postData['password'] : '';
                if ($isCreate === true) {
                    if ($loginAdmin->isSuperAdminRole() === false) {
                        $this->apiError('对不起，您不是超级管理员组，没有创建帐号的权限');
                    }
                    $result = $model->register($roleId, $name, $nickname, $password);
                } else {
                    if ($loginAdmin->isSuperAdminRole() === false && $loginAdmin->id != $postData['id']) {
                        $this->apiError('对不起，您不是超级管理员组，没有操作其他用户帐号的权限');
                    }
                    if ($loginAdmin->isSuperAdminRole() === false && $roleId != $loginAdmin->role_id) {
                        $this->apiError('对不起，您不是超级管理员组，不能修改分组相关信息');
                    }
                    if ($loginAdmin->isSuperAdmin() &&  $loginAdmin->id == $postData['id'] && $roleId != AdministratorRole::SUPER_ADMIN_ROLE_ID) {
                        $this->apiError('对不起，超级管理员，不能修改自己分组');
                    }
                    $result = $model->changeProfile($roleId, $nickname, $password, $isBlock);
                }
                if ($result === false) {
                    $this->apiError($model->getFirstError());
                }
                $this->apiSuccess([
                    'success' => '1'
                ]);
            } else {
                $id = $this->request->get('id', 'int');
                $model = $id ? Administrator::findFirst($id) : new Administrator();
                if (!$model) {
                    $this->apiError('数据不存在');
                }
                $this->apiSuccess([
                    'data' => $model->toArray(['id', 'role_id', 'name', 'nickname', 'is_block', 'is_deleted', 'createdAt', 'updatedAt'])
                ]);
            }

        }
    }

    /**
     * 删除管理员
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
                $model = Administrator::findFirst($id);
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

    /**
     * 微信登录解绑
     *
     */
    public function bindAction()
    {
        if ($this->request->isAjax()) {
            $loginAdmin = Administrator::findFirst($this->adminInfo->id);
            if ($loginAdmin->isSuperAdminRole() === false) {
                $this->apiError('对不起，您不是超级管理员组，没有操作角色的权限');
            }
            $id = trim($this->request->get('id'));
            if (!$id) {
                $this->apiError('信息ID不能为空');
            }
            $administrator = Administrator::findFirst($id);
            if (!$administrator) {
                $this->apiError('管理员信息不存在');
            }
            $administrator->addBehavior(new OperationLog(['id' => $this->adminInfo->id, 'name' => $this->adminInfo->name]));
            if ($administrator->unbindWechat()===false) {
                $this->apiError($administrator->getFirstError());
            }
            $this->apiSuccess([
                'success' => '1'
            ]);

        }
    }


}
