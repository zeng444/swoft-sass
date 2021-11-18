<?php

use Application\Core\Models\Server;
use Application\Core\Models\ServiceDatabase;
use Application\Admin\Components\Db\Criteria;
use Application\Admin\Components\Mvc\Model\Behavior\Log as OperationLog;
use Application\Core\Models\Service;

class ServiceController extends ControllerBase
{

    /**
     * 服务列表列表
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
            $query = Criteria::fromInput($this->di, '\Application\Core\Models\Service', $post);
            $parameters = $query->getParams();
            $parameters = $parameters === null ? [] : $parameters;
            $sortRules = Criteria::getQuerySorting(true);
            $models = Service::find(array_merge($parameters, [
                'offset' => ($page - 1) * $pageSize,
                'limit' => $pageSize,
                'order' => $sortRules ? $sortRules : 'id desc'
            ]));
            $data = [];
            foreach ($models as $key => $model) {
                $data[$key] = $model->toArray(["id", "isAvailable", "serverId", "name", "code", "tag", "host", "createdAt", "updatedAt"]);
                $server = Server::findFirst($model->serverId);
                $data[$key]['serverName'] = $server ? $server->name : '';
                $dbConditions =[
                    'conditions' => 'serviceId = :serviceId:',
                    'bind' => [
                        'serviceId' => $model->id
                    ]
                ];
                $data[$key]['dbCount'] = ServiceDatabase::count($dbConditions);
                $data[$key]['database'] = ServiceDatabase::find($dbConditions)->toArray();

            }
            $this->apiSuccess([
                'data' => $data,
                'count' => (string)Service::count($parameters)
            ]);
        }
    }


    /**
     *  服务列表编辑添加
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
                    $model = Service::findFirst($postData['id']);
                } else {
                    $model = new Service();
                }
                if (!$model) {
                    $this->apiError('数据不存在');
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
                $model = $id ? Service::findFirst($id) : new Service();
                if (!$model) {
                    $this->apiError('数据不存在');
                }
                $this->apiSuccess([
                    'data' => $model->toArray(["id", "isAvailable", "serverId", "name", "code", "tag", "host"])
                ]);
            }

        }
    }

    /**
     * 服务列表删除
     */
    public function deleteAction()
    {
        if ($this->request->isAjax()) {
            $ids = trim($this->request->get('id'));
            $ids = explode(',', $ids);
            $success = 0;
            foreach ($ids as $id) {
                $model = Service::findFirst($id);
                if ($model) {
                    $exits = ServiceDatabase::findFirst([
                        'conditions' => 'serviceId = :serviceId:',
                        'bind' => [
                            'serviceId' => $id
                        ]
                    ]);
                    if ($exits) {
                        $this->apiError('存在服务数据库，请先删除数据库');
                    }
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
