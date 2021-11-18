<?php
use Application\Admin\Components\Db\Criteria;
use Application\Core\Models\Configuration;

class MainController extends ControllerBase
{

    /**
     * 最近更新
     */
    public function indexAction()
    {
        if ($this->request->isAjax()) {
            $url = $this->getDI()->get('config')->platform_service->baseUrl . 'platform/main?key=' . Configuration::CONFIGURATION_CACHE_KEY;
            $content = file_get_contents($url);
            $data = json_decode($content);
            $this->apiSuccess([
                'data' => $data,
                'url' => $url,
            ]);
        } else {
            $this->view->platformServiceUrl = $this->getDI()->get('config')->platform_service->baseUrl;
            $this->view->configKey = Configuration::CONFIGURATION_CACHE_KEY;
        }
    }


}
