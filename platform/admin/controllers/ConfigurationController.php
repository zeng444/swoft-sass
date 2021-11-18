<?php
use Phalcon\Mvc\View;
use Application\Admin\Components\Db\Criteria;
use Application\Admin\Components\Mvc\Model\Behavior\Log as OperationLog;
use Application\Core\Models\Configuration;

class ConfigurationController extends ControllerBase
{

    /**
     * 参数配置列表
     */
    public function indexAction()
    {
        if ($this->request->isAjax()) {
            $page = $this->request->get('page', 'int');
            $page = $page > 0 ? $page : 1;
            $pageSize = $this->request->get('pageSize', 'int');
            $pageSize = $pageSize > 0 ? $pageSize : Criteria::PAGE_SIZE;
            $post = [];
            $post = array_merge($_REQUEST, $post);
            $query = Criteria::fromInput($this->di, '\Application\Core\Models\Configuration', $post);
            $parameters = $query->getParams();
            $parameters = $parameters === null ? [] : $parameters;
            $sortRules = Criteria::getQuerySorting(true);
            $models = Configuration::find(array_merge($parameters, [
                'offset' => ($page - 1) * $pageSize,
                'limit' => $pageSize,
                'order' => $sortRules ? $sortRules : 'id desc'
            ]));
            $data = [];
            foreach ($models as $key => $model) {
                $data[$key] = $model->toArray(["id", "key", "value", "desc", "createdAt", "updatedAt"]);
            }
            $this->apiSuccess([
                'data' => $data,
                'count' => (string)Configuration::count($parameters)
            ]);
        }
    }

    /**
     *  参数配置编辑添加
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
                if (isset($postData['id']) && $postData['id']) {
                    $model = Configuration::findFirst($postData['id']);
                } else {
                    $model = new Configuration();
                }
                if(!$model){
                    $this->apiError('数据不存在');
                }
                $model->addBehavior(new OperationLog(['id' => $this->adminInfo->id, 'name' => $this->adminInfo->name]));
                if ($model->save($postData) === false) {
                    $this->apiError($model->getFirstError());
                }
                Configuration::makeCache();
                $this->apiSuccess([
                    'success' => '1'
                ]);
            } else {
                $id = $this->request->get('id', 'int');
                $model = $id ? Configuration::findFirst($id) : new Configuration();
                if (!$model) {
                    $this->apiError('数据不存在');
                }
                $this->apiSuccess([
                    'data' => $model->toArray(["id", "key", "value", "desc", "createdAt", "updatedAt"])
                ]);
            }

        }
    }

    /**
     * 参数配置删除
     */
    public function deleteAction()
    {
        if ($this->request->isAjax()) {
            $ids = trim($this->request->get('id'));
            $ids = explode(',', $ids);
            $success = 0;
            foreach ($ids as $id) {
                $model = Configuration::findFirst($id);
                $model->addBehavior(new OperationLog(['id' => $this->adminInfo->id, 'name' => $this->adminInfo->name]));
                if ($model) {
                    if ($model->delete() === false) {
                        $this->apiError($model->getFirstError());
                    }
                    $success++;
                }
            }
            Configuration::makeCache();
            $this->apiSuccess([
                'success' => (string)$success
            ]);
        }
    }


}
