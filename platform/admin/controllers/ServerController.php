<?php

use Application\Core\Models\ServiceDatabase;
use Application\Core\Models\Service;
use Application\Admin\Components\Db\Criteria;
use Application\Admin\Components\Mvc\Model\Behavior\Log as OperationLog;
use Application\Core\Models\Server;

class ServerController extends ControllerBase
{

    /**
     * 服务器列表
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
            $query = Criteria::fromInput($this->di, '\Application\Core\Models\Server', $post);
            $parameters = $query->getParams();
            $parameters = $parameters === null ? [] : $parameters;
            $sortRules = Criteria::getQuerySorting(true);
            $models = Server::find(array_merge($parameters, [
                'offset' => ($page - 1) * $pageSize,
                'limit' => $pageSize,
                'order' => $sortRules ?: 'id desc'
            ]));
            $data = [];
            foreach ($models as $key => $model) {
                $data[$key] = $model->toArray(["id", "name", "domain", "ip", "createdAt", "updatedAt"]);
                $data[$key]['serviceCount'] = Service::count([
                   'conditions'=>'serverId = :serverId:',
                   'bind'=>[
                       'serverId'=>$model->id
                   ]
                ]);
                $data[$key]['dbCount'] = ServiceDatabase::count([
                    'conditions'=>'serverId = :serverId:',
                    'bind'=>[
                        'serverId'=>$model->id
                    ]
                ]);
            }
            $this->apiSuccess([
                'data' => $data,
                'count' => (string)Server::count($parameters)
            ]);
        }
    }


    /**
     *  服务器编辑添加
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
                    $model = Server::findFirst($postData['id']);
                } else {
                    $model = new Server();
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
                $model = $id ? Server::findFirst($id) : new Server();
                if (!$model) {
                    $this->apiError('数据不存在');
                }
                $this->apiSuccess([
                    'data' => $model->toArray(["id", "name", "domain", "ip"])
                ]);
            }

        }
    }

    /**
     * 服务器删除
     */
    public function deleteAction()
    {
        if ($this->request->isAjax()) {
            $ids = trim($this->request->get('id'));
            $ids = explode(',', $ids);
            $success = 0;
            foreach ($ids as $id) {
                $model = Server::findFirst($id);
                if ($model) {
                    $exist = \Application\Core\Models\Service::findFirst([
                        'conditions'=>'serverId = :serverId:',
                        'bind'=>[
                            'serverId'=>$id
                        ]
                    ]);
                    if($exist){
                        $this->apiError("服务器下存在服务，请先删除服务");
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
