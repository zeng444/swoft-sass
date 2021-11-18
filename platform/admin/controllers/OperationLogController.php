<?php
use Application\Admin\Components\Db\Criteria;
use Application\Core\Models\OperationLog;

class OperationLogController extends ControllerBase
{

    /**
     * 管理员日志
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
            $query = Criteria::fromInput($this->di, '\Application\Core\Models\OperationLog', $post);
            $parameters = $query->getParams();
            $parameters = $parameters===null ? []: $parameters;
            $sortRules = Criteria::getQuerySorting(true);
            $models = OperationLog::find(array_merge($parameters, [
                'offset' => ($page - 1) * $pageSize,
                'limit' => $pageSize,
                'order' => $sortRules ? $sortRules : 'id desc'
            ]));
            $data = [];
            foreach ($models as $key => $model) {
                $data[$key] = $model->toArray(["id","ip","action","administrator_id","administrator_name","event","data","createdAt","updatedAt"]);
            }
            $this->apiSuccess([
                'data' => $data,
                'count' => (string)OperationLog::count($parameters)
            ]);
        }
    }

    



}
