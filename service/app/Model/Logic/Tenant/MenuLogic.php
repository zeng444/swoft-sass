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
     * System menu
     * @return array
     * @author Robert
     */
    public function systemTree(): array
    {
        $menus = self::getChildNode();
        $data = [];
        foreach ($menus as $menu) {
            //hack 2021-11-11暂时不开放数据解析权限，让管理员能看即可
            if($menu['name']==='数据解析'){
                continue;
            }
            $item = $menu;
            $item['children'] = [];
            $children = self::getChildNode($menu['id']);
            foreach ($children as $child) {
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
            if ($userMenuIds && !in_array($menu['id'], $userMenuIds)) {
                continue;
            }
            $item = $menu;
            $item['children'] = [];
            $children = self::getChildNode($menu['id']);
            foreach ($children as $child) {
                if (!$user || in_array($child['id'], $userRoleMenuIds) || $user->getIsSuper() === 1) {
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
