<?php
use Application\Admin\Components\Db\Criteria;
use Application\Core\Models\PlatformMenu;

class MenuController extends ControllerBase
{

    public function indexAction()
    {
        if ($this->request->isAjax()) {
            $page = $this->request->get('page', 'int');
            $keyword = trim($this->request->get('keyword'));
            if (!$keyword) {
                $this->apiError('关键字为空');
            }
            $page = $page > 0 ? $page : 1;
            $pageSize = $this->request->get('pageSize', 'int');
            $pageSize = $pageSize > 0 ? $pageSize : Criteria::PAGE_SIZE;
            $parameters = [];
            $sortRules = Criteria::getQuerySorting(true);
            $parameters['conditions'] = ' (name like :name:  or link like :name:)';
            $parameters['conditions'] .= ' and parent_id is not null';
            $parameters['bind'] = [
                'name' => '%' . $keyword . '%'
            ];
            $models = PlatformMenu::find(array_merge($parameters, [
                'offset' => ($page - 1) * $pageSize,
                'limit' => $pageSize,
                'order' => $sortRules ? $sortRules : 'id desc'
            ]));
            $data = [];
            foreach ($models as $key => $model) {
                $data[$key] = $model->toArray(['id', 'name', 'link']);
            }
            $this->apiSuccess([
                'data' => $data,
                'keyword' => $keyword,
                'count' => (string)PlatformMenu::count($parameters)
            ]);
        }
    }

}
