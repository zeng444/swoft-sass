<?php declare(strict_types=1);

namespace App\Model\Logic\Tenant;

use App\Exception\LogicException;
use App\Model\Entity\AclRoute;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DB;
use Throwable;

/**
 * Class AclRouteLogic
 * @Bean()
 * @author Robert
 * @package App\Model\Logic\Tenant
 */
class AclRouteLogic
{

    /**
     * 按配置删除或者存档路由
     * @param array $routes [["name"=>"账户管理","route"=>"GET:/accounts"]]
     * @return bool
     * @throws Throwable
     * @author Robert
     */
    public function build(array $routes): bool
    {
        //差集弄出要删除的
        $aclRoutes = $this->getGlobalRoutes(['id', 'route']);
        $routesList = array_column($routes, 'route');
        $diff = array_filter($aclRoutes, function ($item) use ($routesList) {
            return !in_array($item['route'], $routesList);
        });
        $diff = array_column($diff, 'id');
        //写入新的
        $new = [];
        $date = date('Y-m-d H:i:s');
        foreach ($routes as $item) {
            $new[] = [
                'tenantId' => 0,
                'menuId' => 0,
                'updatedAt' => $date,
                'createdAt' => $date,
                'name' => $item['name'],
                'route' => $item['route'],
                'key' => $this->hashRoute($item['route']),
            ];
        }
        return DB::transaction(static function () use ($diff, $routes, $new) {
            if ($diff) {
                if (!AclRoute::whereIn('id', $diff)->where('tenantId', 0)->delete()) {
                    throw new LogicException('更细系统路由失败');
                }
            }
            return !$new || AclRoute::insertOrUpdate($new, true, ['createdAt', 'tenantId', 'menuId']);
        });
    }

    /**
     * @param $route
     * @return string
     * @author Robert
     */
    private function hashRoute($route): string
    {
        return substr(md5($route), 0, 16);
    }


    /**
     * 获取全局所有接口路由
     * @param array|string[] $columns
     * @return array
     * @author Robert
     */
    public function getGlobalRoutes(array $columns = ['*']): array
    {
        return AclRoute::where('tenantId', '=', 0)->get($columns)->toArray();
    }

}
