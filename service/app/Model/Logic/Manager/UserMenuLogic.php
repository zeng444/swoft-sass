<?php declare(strict_types=1);

namespace App\Model\Logic\Manager;

use App\Exception\LogicException;
use App\Model\Entity\AclRoute;
use App\Model\Entity\UserMenu;
use App\Model\Entity\UserRole;
use App\Model\Entity\UserRoleRoute;
use App\Model\Logic\Tenant\MenuLogic;
use App\Model\Logic\Tenant\UserRoleLogic;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DB;

/**
 * Author:Robert
 *
 * Class UserLogic
 * @Bean()
 * @package App\Model\Logic\Manager
 */
class UserMenuLogic
{

    /**
     * @param int $tenantId
     * @return array
     * @author Robert
     */
    public function menus(int $tenantId): array
    {
        /** @var MenuLogic $menuLogic */
        $menuLogic = \Swoft::getBean(MenuLogic::class);
        $menuNodes = $menuLogic->systemTree(true);
        $userMenus = UserMenu::where('tenantId', $tenantId)->get(['menuId'])->toArray();
        $userMenus = $userMenus ? array_column($userMenus, 'menuId') : [];
        foreach ($menuNodes as &$menuNode) {
            foreach ($menuNode['children'] as &$child) {
                if (!$userMenus ||in_array($child['id'], $userMenus)) {
                    $menuNode['selected'] = 1;
                } else {
                    $menuNode['selected'] = 0;
                }
            }
        }
        return $menuNodes;
    }

    /**
     * 调整租客可用的功能
     * @param int $tenantId
     * @param array $menuIds
     * @return bool
     * @author Robert
     */
    public function setMenu(int $tenantId, array $menuIds): bool
    {
        $date = date('Y-m-d H:i:s');
        //允许访问的栏目
        $allowedMenus = [];
        foreach ($menuIds as $menuId) {
            $allowedMenus[] = [
                'tenantId' => $tenantId,
                'menuId' => $menuId,
                'createdAt' => $date,
                'updatedAt' => $date,
            ];

        }
        //允许访问的栏目路由
        $aclRoutes = AclRoute::where('tenantId', 0)->whereIn('menuId', $menuIds)->get(['route', 'key'])->toArray();
        //当前角色可访问路由
        $userRoleRoutes = UserRoleRoute::where('tenantId', $tenantId)->get(['id', 'route']);
        //剔除所有角色不可访问路由
        $deleteList = [];
        $routes = $aclRoutes ? array_column($aclRoutes, 'route') : [];
        foreach ($userRoleRoutes as $userRoleRoute) {
            if (!in_array($userRoleRoute->getRoute(), $routes)) {
                $deleteList[] = $userRoleRoute->getId();
            }
        }
        //写入超级角色可访问路由
        $superRoleId = UserRole::where([['tenantId', $tenantId], ['isSuper', 1]])->value('id');
        $superRoleRoutes = [];
        if ($superRoleId) {
            foreach ($aclRoutes as $aclRoute) {
                $superRoleRoutes[] = [
                    'tenantId' => $tenantId,
                    'userRoleId' => $superRoleId,
                    'route' => $aclRoute['route'],
                    'key' => $aclRoute['propertyKey'],
                    'createdAt' => $date,
                    'updatedAt' => $date,
                ];
            }
        }
        $result =  DB::transaction(static function () use ($tenantId, $allowedMenus, $deleteList, $superRoleRoutes) {
            UserRoleRoute::whereIn('id', $deleteList)->delete();
            UserMenu::where('tenantId', $tenantId)->delete();
            if ($allowedMenus) {
                if (!UserMenu::insertOrUpdate($allowedMenus)) {
                    throw new LogicException('调整权限失败');
                }
            }
            if ($superRoleRoutes) {
                if (!UserRoleRoute::insertOrUpdate($superRoleRoutes, true, ['createdAt'])) {
                    throw new LogicException('调整权限失败');
                }
            }
            return true;
        });
        //刷新角色缓存
        $userRoles = UserRole::where([['tenantId', $tenantId]])->get(['id']);
        foreach ($userRoles as $userRole) {
            \Swoft::getBean(UserRoleLogic::class)->cleanRoleMenuCache($userRole->getId());
        }
        return $result;
    }

}
