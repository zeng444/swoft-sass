<?php declare(strict_types=1);

namespace App\Model\Logic;

use App\Rpc\Lib\Tenant\AclRouteInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;

/**
 * Class AclRouteLogic
 * @Bean()
 * @author Robert
 * @package App\Model\Logic
 */
class AclRouteLogic
{

    /**
     * @Reference("biz.pool")
     * @var AclRouteInterface
     */
    protected $aclRouteService;

    /**
     * @author Robert
     */
    public function build(): bool
    {
        $httpRouter = \Swoft::getBean('httpRouter');
        $routes = $httpRouter->getRoutes();
        $routeIgnore = config('acl.ignore', []);
        $data = [];
        foreach ($routes as $route) {
            if (!$route['path'] || strpos($route['path'], '/__devtool') === 0 || in_array($route['method'] . ':' . $route['path'], $routeIgnore)) {
                continue;
            }
            $data[] = [
                'name' => $route['name'],
                'route' => $route['method'] . ":" . $route['path'],
            ];
        }
        return $this->aclRouteService->build($data);
    }

}
