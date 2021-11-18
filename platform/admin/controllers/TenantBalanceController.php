<?php
use Phalcon\Mvc\View;
use Application\Admin\Components\Db\Criteria;
use Application\Core\Models\Tenant;
use Application\Core\Models\TenantBalance;

/**
 * Class TenantBalanceController
 * @author Robert
 */
class TenantBalanceController extends ControllerBase
{

    /**
     * 资金明细列表
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
            $query = Criteria::fromInput($this->di, '\Application\Core\Models\TenantBalance', $post);
            $parameters = $query->getParams();
            $parameters = $parameters===null ? []: $parameters;
            $sortRules = Criteria::getQuerySorting(true);
            $models = TenantBalance::find(array_merge($parameters, [
                'offset' => ($page - 1) * $pageSize,
                'limit' => $pageSize,
                'order' => $sortRules ? $sortRules : 'id desc'
            ]));
            $data = [];
            foreach ($models as $key => $model) {
                $data[$key] = $model->toArray(["id", "tenantId", "outTradeNo", "transactionId", "biz", "bizData", "fee", "remark", "paidAt", "createdAt", "updatedAt"]);
                $tenant = Tenant::findFirst($model->tenantId);
                $data[$key]['tenantName'] = $tenant ? $tenant->name : "";
            }

            $tenantName = '';
            if(isset($post['tenantId'])){
                $tenant = Tenant::findFirst($post['tenantId']);
                $tenantName = $tenant ? $tenant->name :'';
            }
            $this->apiSuccess([
                'data' => $data,
                'tenantName' => $tenantName,
                'count' => (string)TenantBalance::count($parameters)
            ]);
        }
    }

    


}
