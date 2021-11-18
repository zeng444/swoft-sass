<?php

use Application\Core\Models\Server;
use Application\Admin\Components\Db\Criteria;
use Application\Admin\Components\Mvc\Model\Behavior\Log as OperationLog;
use Application\Core\Models\ServiceDatabase;
use Application\Core\Models\Service;

class ServiceDatabaseController extends ControllerBase
{

    /**
     * 数据库列表
     */
    public function indexAction()
    {
        if ($this->request->isAjax()) {
            $page = $this->request->get('page', 'int');
            $page = $page > 0 ? $page : 1;
            $pageSize = $this->request->get('pageSize', 'int');
            $pageSize = $pageSize > 0 ? $pageSize : Criteria::PAGE_SIZE;
            $post = [];
            $post = array_merge($_POST, $post);
            $query = Criteria::fromInput($this->di, '\Application\Core\Models\ServiceDatabase', $post);
            $parameters = $query->getParams();
            $parameters = $parameters === null ? [] : $parameters;
            $sortRules = Criteria::getQuerySorting(true);
            $models = ServiceDatabase::find(array_merge($parameters, [
                'offset' => ($page - 1) * $pageSize,
                'limit' => $pageSize,
                'order' => $sortRules ?: 'id desc'
            ]));
            $data = [];
            foreach ($models as $key => $model) {
                $data[$key] = $model->toArray(["id", "serverId", "serviceId", "database", "createdAt", "updatedAt"]);
                $service = Service::findFirst($model->serviceId);
                $data[$key]['serviceName'] = $service ? $service->name : "";
                $server = Server::findFirst($model->serverId);
                $data[$key]['serverName'] = $server ? $server->name : '';
            }
            $this->apiSuccess([
                'data' => $data,
                'count' => (string)ServiceDatabase::count($parameters)
            ]);
        }
    }


    /**
     *  数据库编辑添加
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
                    $model = ServiceDatabase::findFirst($postData['id']);
                } else {
                    $model = new ServiceDatabase();
                }
                if (!$model) {
                    $this->apiError('数据不存在');
                }
                $postData['serverId'] = '';
                if (isset($postData['serviceId']) && $postData['serviceId'] > 0) {
                    $service = Service::findFirst($postData['serviceId']);
                    $postData['serverId'] = $service ? $service->serverId : '';
                }
                $model->addBehavior(new OperationLog(['id' => $this->adminInfo->id, 'name' => $this->adminInfo->name]));
                if ($model->save($postData) === false) {
                    $this->apiError($model->getFirstError());
                }
                $this->apiSuccess([
                    'success' => '1'
                ]);
            } else {
                $id = $this->request->get('id', 'int');
                $model = $id ? ServiceDatabase::findFirst($id) : new ServiceDatabase();
                if (!$model) {
                    $this->apiError('数据不存在');
                }
                $this->apiSuccess([
                    'data' => $model->toArray(["id", "serverId", "serviceId", "database"])
                ]);
            }

        }
    }

    /**
     * 数据库删除
     */
    public function deleteAction()
    {
        if ($this->request->isAjax()) {
            $ids = trim($this->request->get('id'));
            $ids = explode(',', $ids);
            $success = 0;
            foreach ($ids as $id) {
                $model = ServiceDatabase::findFirst($id);
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
