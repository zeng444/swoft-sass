<?php

use App\Rpc\Lib\Manager\AmountInterface;
use Application\Admin\Components\Db\Criteria;
use App\Rpc\Lib\Manager\MessageStatisticsInterface;
use Application\Core\Models\Tenant;
use App\Rpc\Lib\Manager\VoiceStatisticsInterface;

/**
 * Class PhoneController
 * @author Robert
 */
class PluginController extends ControllerBase
{
    /**
     * 租客功能列表
     */
    public function indexAction()
    {
        if ($this->request->isAjax()) {
            if ($this->request->isPost()) {
                $id = $this->request->get('id');
                $tenant = Tenant::findFirst($id);
                if (!$tenant) {
                    $this->apiError('ID is not empty');
                }
                $list = $this->getDI()->get('rpc')
                    ->tenantDispatch((int)$id, \App\Rpc\Lib\Manager\UserMenuInterface::class, 'menus', [(int)$id]);
                foreach ($list as &$item){
                    $item['title'] = $item['name'];
                    $item['expand'] = true;
                    $item['checked'] = $item['selected'] ==1;
                    unset($item['name']);
                    foreach ($item['children'] as &$it){
                        $it['title'] = $it['name'];
                        $it['checked'] = $it['selected'] ==1;
                        unset($it['name']);
                    }
                }
                $this->apiSuccess([
                    'data' => $list,
                    'count' => 100,
                    'info' => $tenant->name,
                ]);
            }
        }
    }
    
    /**
     * 租客功能管理
     */
    public function postAction()
    {
        if ($this->request->isAjax()) {
            if ($this->request->isPost()) {
                $id = $this->request->get('id');
                $tenant = Tenant::findFirst($id);
                if (!$tenant) {
                    $this->apiError('ID is not empty');
                }
                $menuId = $this->request->get('menuId');
                $menuId = $menuId?:[];
                $this->getDI()->get('rpc')
                    ->tenantDispatch((int)$id, \App\Rpc\Lib\Manager\UserMenuInterface::class, 'setMenu', [(int)$id,$menuId]);
                $this->apiSuccess([
                    'success' => '1'
                ]);
            }
        }
    }

}