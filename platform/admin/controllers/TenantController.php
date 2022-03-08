<?php

use App\Rpc\Lib\Manager\UserInterface;
use Application\Admin\Components\Db\Criteria;
use Application\Admin\Components\Mvc\Model\Behavior\Log as OperationLog;
use Application\Core\Models\CustomerManager;
use Application\Core\Models\Tenant;
use Application\Core\Models\TenantService;
use Application\Core\Models\Server;
use Application\Core\Models\Service;
use Application\Core\Models\ServiceDatabase;
use App\Rpc\Lib\Manager\SystemSettingInterface;

/**
 * Class TenantController
 * @author Robert
 */
class TenantController extends ControllerBase
{

    /**
     * 租客列表
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
            $query = Criteria::fromInput($this->di, '\Application\Core\Models\Tenant', $post);
            $parameters = $query->getParams();
            $parameters = $parameters === null ? [] : $parameters;
            $sortRules = Criteria::getQuerySorting(true);
            $models = Tenant::find(array_merge($parameters, [
                'offset' => ($page - 1) * $pageSize,
                'limit' => $pageSize,
                'order' => $sortRules ?: 'id desc',
            ]));
            $data = [];
            foreach ($models as $key => $model) {
                $data[$key] = $model->toArray([
                    "id",
                    "isAvailable",
                    "name",
                    "account",
                    "province",
                    "city",
                    "linkman",
                    "contact",
                    "beginAt",
                    "endAt",
                    "createdAt",
                    "updatedAt",
                ]);
            }
            $this->apiSuccess([
                'data' => $data,
                'count' => (string)Tenant::count($parameters),
            ]);
        }
    }


    /**
     * 是否可用
     */
    public function availableAction()
    {
        if ($this->request->isAjax()) {
            if ($this->request->isPost()) {
                $postData = array_map(function ($val) {
                    if (trim($val) === '') {
                        $val = null;
                    }
                    return $val;
                }, $_POST);

                $model = Tenant::findFirst($postData['id']);
                if (!$model) {
                    $this->apiError('数据不存在');
                }
                $model->isAvailable = $postData['isAvailable'];
                $model->addBehavior(new OperationLog(['id' => $this->adminInfo->id, 'name' => $this->adminInfo->name]));
                if(!$model->save()){
                    $this->apiError($model->getFirstError());
                }
                $this->apiSuccess([
                    'success' => '1',
                ]);
            }
        }
    }

    /**
     * 推荐数据库
     */
    public function databaseAction()
    {
        if ($this->request->isAjax()) {
            if ($this->request->isPost()) {
                $databaseId = ServiceDatabase::automaticSelectDb();
                if ($databaseId <= 0) {
                    $this->apiError('暂无数据库配置，无法自动推荐');
                }
                $this->apiSuccess([
                    'data' => $databaseId,
                ]);
            }
        }
    }

    /**
     *  租客编辑添加
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
                    $model = Tenant::findFirst($postData['id']);
                    $customerManager = CustomerManager::findFirst($postData['id']);
                    $customerManager = $customerManager ?: new CustomerManager();

                } else {
                    $model = new Tenant();
                    $customerManager = new CustomerManager();

                }
                if (!$model) {
                    $this->apiError('数据不存在');
                }
                $model->addBehavior(new OperationLog(['id' => $this->adminInfo->id, 'name' => $this->adminInfo->name]));
                $name = (string)($postData['name'] ?? '');
                $account = (string)($postData['account'] ?? '');
                $password = (string)($postData['password'] ?? '');
                $province = (string)($postData['province'] ?? '');
                $city = (string)($postData['city'] ?: '');
                $linkman = (string)($postData['linkman'] ?? '');
                $contact = (string)($postData['contact'] ?? '');
                $beginAt = (string)($postData['beginAt'] ?? '');
                $endAt = (string)($postData['endAt'] ?? '');
                $isAvailable = (int)($postData['isAvailable'] ?? '');
                $database = (int)($postData['database'] ?? 0);
                $allowedUsers = (int)($postData['allowedUsers'] ?? 1);
                $managerName = (string)($postData['managerName'] ?? '');
                $managerMobile = (string)($postData['managerMobile'] ?? '');
                if ($model->touch($isAvailable == 1, $name, $account, $password, $database, $province, $city, $linkman, $contact, $beginAt, $endAt, [
                        'allowedUsers' => $allowedUsers,
                    ]) === false) {
                    $this->apiError($model->getFirstError());
                }
                $customerManager->tenantId = (int)$model->id;
                $customerManager->name = $managerName;
                $customerManager->mobile = $managerMobile;
                if (!$customerManager->save()) {
                    $this->apiError($model->getFirstError());
                }

                $this->apiSuccess([
                    'success' => '1',
                ]);
            } else {
                $id = $this->request->get('id', 'int');
                $model = $id ? Tenant::findFirst($id) : new Tenant();
                if (!$model) {
                    $this->apiError('数据不存在');
                }
                $data = $model->toArray([
                    "id",
                    "isAvailable",
                    "account",
                    "name",
                    "province",
                    "city",
                    "linkman",
                    "contact",
                    "beginAt",
                    "endAt",
                ]);
                if ($id) {
                    $data['database'] = TenantService::getTenantDatabaseId($id) ?: '';
                    $serviceDatabase = ServiceDatabase::findFirst($data['database']);
                    $server = Server::findFirst($serviceDatabase->serverId);
                    $service = Service::findFirst($serviceDatabase->serviceId);
                    $data['databaseName'] = ($server ? $server->name : "") . " - " . ($service ? $service->name : '') . " - [" . ($serviceDatabase ? $serviceDatabase->database : "") . "]";
                    $setting = $this->getDI()->get('rpc')
                        ->tenantDispatch((int)$id, SystemSettingInterface::class, 'getAll', [(int)$id]);
                    $customerManager = CustomerManager::findFirst($id);
                    $data['managerName'] = $customerManager ? $customerManager->name : '';
                    $data['managerMobile'] = $customerManager ? $customerManager->mobile : '';
                    $data['allowedUsers'] = $setting['allowedUsers'] ?? 0;
                } else {
                    $data['database'] = '';
                    $data['databaseName'] = '';
                    $data['managerName'] = '';
                    $data['managerMobile'] = '';
                    $data['allowedUsers'] = 1;
                }
                $this->apiSuccess([
                    'data' => $data,
                ]);
            }

        }
    }



    /**
     * 租客详情
     */
    public function viewAction()
    {
        if ($this->request->isAjax()) {
            if ($this->request->isPost()) {
                $id = $this->request->get('id', 'int');
                $password = $this->request->get('password');
                $this->getDI()->get('rpc')
                    ->tenantDispatch((int)$id, UserInterface::class, 'resetSuperPassword', [(int)$id, $password]);
                $this->apiSuccess([
                    'success' => '1',
                ]);
            }else{
                $id = $this->request->get('id', 'int');
                $model = $id ? Tenant::findFirst($id) : new Tenant();
                if (!$model) {
                    $this->apiError('数据不存在');
                }
                $data = $model->toArray([
                    "id",
                    "isAvailable",
                    "name",
                    "account",
                    "province",
                    "city",
                    "linkman",
                    "contact",
                    "beginAt",
                    "endAt",
                ]);
                $databaseId = TenantService::getTenantDatabaseId($id);
                if ($databaseId) {
                    $serviceDatabase = ServiceDatabase::findFirst($databaseId);
                    $service = Service::findFirst($serviceDatabase->serviceId);

                    $server = Server::findFirst($service->serverId);
                    $data['serverModel'] = $server ? $server->toArray() : [];
                    $data['serviceModel'] = $service ? $service->toArray() : [];
                    $data['dbName'] = $serviceDatabase ? $serviceDatabase->database : '';

                } else {
                    $data['serverModel'] = [];
                    $data['serviceModel'] = [];
                    $data['dbName'] = '';
                }
                $setting = $this->getDI()->get('rpc')
                    ->tenantDispatch((int)$id, SystemSettingInterface::class, 'getAll', [(int)$id]);
                $data['allowedUsers'] = $setting['allowedUsers'] ?? 0;

                $adminInfo = $this->getDI()->get('rpc')
                                  ->tenantDispatch((int)$id, UserInterface::class, 'superAdminInfo', [(int)$id, ['password']]);
                $data['password'] =  $adminInfo['password'] ??'';

                $this->apiSuccess([
                    'data' => $data,
                ]);
            }

        }
    }

    /**
     * 租客删除
     */
    public function deleteAction()
    {
        if ($this->request->isAjax()) {
            $ids = trim($this->request->get('id'));
            $ids = explode(',', $ids);
            $success = 0;
            foreach ($ids as $id) {
                $model = Tenant::findFirst($id);
                if ($model) {
                    $model->addBehavior(new OperationLog([
                        'id' => $this->adminInfo->id,
                        'name' => $this->adminInfo->name,
                    ]));
                    if ($model->delete() === false) {
                        $this->apiError($model->getFirstError());
                    }
                    $success++;
                }
            }
            $this->apiSuccess([
                'success' => (string)$success,
            ]);
        }
    }

}
