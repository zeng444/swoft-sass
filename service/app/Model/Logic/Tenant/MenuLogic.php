<?php declare(strict_types=1);


namespace App\Model\Logic\Tenant;

use App\Exception\LogicException;
use App\Model\Entity\Menu;
use App\Model\Entity\UserMenu;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Author:Robert
 *
 * Class MenuLogic
 * @Bean()
 * @package App\Model\Logic\Tenant
 */
class MenuLogic
{

    /**
     * @return array
     * @author Robert
     */
    public function getTenantMenu(): array
    {
        $userMenus = UserMenu::limit(200)->get(['menuId'])->toArray();
        return array_column($userMenus, 'menuId');
    }

    /**
     * Author:Robert
     *
     * @param bool $isAll 管理端平台需要true，获得所有可选菜单
     * @return array
     */
    public function systemTree(bool $isAll = false): array
    {
        $userMenuIds = $this->getTenantMenu();
        $menus = self::getChildNode();
        $data = [];
        foreach ($menus as $menu) {
            $item = $menu;
            $item['children'] = [];
            $children = self::getChildNode($menu['id']);
            foreach ($children as $child) {
                if ($isAll===false && $userMenuIds && !in_array($child['id'], $userMenuIds)) {
                    continue;
                }
                $item['children'][] = $child;
            }
            if ($item['children']) {
                $data[] = $item;
            }
        }
        return $data;
    }

    /**
     * 获取菜单
     * @param int $userId
     * @return array
     * @throws LogicException
     * @author Robert
     */
    public function tree(int $userId): array
    {
        $userMenuIds = $this->getTenantMenu();
        /** @var UserLogic $userLogic */
        $userLogic = \Swoft::getBean(UserLogic::class);
        if ($userId > 0) {
            $user = $userLogic->getUserById($userId, ['roleId', 'isSuper']);
            if(!$user){
                throw new LogicException('用户不存在');
            }
            $userRoleMenuIds = (\Swoft::getBean(UserRoleLogic::class))->userRoleMenus($user->getRoleId());
        }else{
            $userRoleMenuIds = [];
            $user = null;
        }
        $menus = self::getChildNode();
        $data = [];
        foreach ($menus as $menu) {
            $item = $menu;
            $item['children'] = [];
            $children = self::getChildNode($menu['id']);
            foreach ($children as $child) {
                if ($userMenuIds && !in_array($child['id'], $userMenuIds)) {
                    continue;
                }
                if (!$user || in_array($child['id'], $userRoleMenuIds)) {
                    $item['children'][] = $child;
                }
            }
            if ($item['children']) {
                $data[] = $item;
            }
        }
        return $data;
    }

    /**
     * Author:Robert
     *
     * @param int|null $parenId
     * @return array
     */
    private static function getChildNode(int $parenId = null): array
    {
        $model = Menu::where([['isVisible', '=', 1], ['tenantId', '=', 0]]);
        if ($parenId === null) {
            $model->where('parentId', $parenId);
        } else {
            $model->where('parentId', '=', $parenId);
        }
        $model->orderBy('sort', 'ASC');
        return $model->get(['id', 'name', 'icon', 'link'])->toArray();
    }
}
