<?php declare(strict_types=1);

namespace App\Model\Logic\Tenant;

use App\Exception\LogicException;
use App\Model\Entity\AclRoute;
use App\Model\Entity\Menu;
use App\Model\Entity\User;
use App\Model\Entity\UserRole;
use App\Model\Entity\UserRoleRoute;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DB;
use Swoft\Redis\Redis;
use Throwable;

/**
 * Class UserRoleLogic
 * @Bean()
 * @author Robert
 * @package App\Model\Logic\Tenant
 */
class UserRoleLogic
{

    /**
     * @param int $roleId
     * @param string $name
     * @param array $menuIds
     * @param string $reader
     * @param string $remark
     * @return array
     * @throws LogicException
     * @author Robert
     */
    public function edit(int $roleId, string $name, array $menuIds, string $reader, string $remark = ''): array
    {
        if (!in_array($reader, array_keys(\App\Model\Constant\UserRole::READER_MAP))) {
            throw new LogicException('不存在的reader');
        }
        $userRole = $this->getRoleById($roleId, ['id', 'isSuper', 'reader', 'users', 'name', 'remark', 'createdAt', 'updatedAt']);
        if (!$userRole) {
            throw new LogicException('不存在的角色');
        }
        $isSuper = $userRole->getIsSuper() == 1;
        $userMenuIds = \Swoft::getBean(MenuLogic::class)->getTenantMenu();
        $userRole->setName($name);
        $userRole->setReader($reader);
        $userRole->setRemark($remark);
        if (!$isSuper) {
            //差集
            $menuIds = AclRoute::whereIn('menuId', $menuIds)->where('tenantId', 0)->get(['key'])->toArray();
            $routeKeys = $menuIds ? array_column($menuIds, 'propertyKey') : [];
            $currentRoutes = UserRoleRoute::where('userRoleId', $roleId)->get(['id', 'key'])->toArray();
            $diff = array_filter($currentRoutes, function ($item) use ($routeKeys) {
                return !in_array($item['propertyKey'], $routeKeys);
            });
            $diff = array_column($diff, 'id');
            $aclRoutes = AclRoute::whereIn('key', $routeKeys)->where('tenantId', 0)->get(['route', 'key', 'menuId'])->toArray();
        } else {
            $aclRoutes = [];
            $diff = [];
        }
        $date = date('Y-m-d H:i:s');
        /** @var UserRole $userRole */
        $userRole = DB::transaction(function () use ($isSuper, $userRole, $userMenuIds, $aclRoutes, $diff, $date) {
            if (!$userRole->save()) {
                throw new LogicException('创建角色失败');
            }
            if (!$isSuper) {
                if ($diff) {
                    if (!UserRoleRoute::whereIn('id', $diff)->delete()) {
                        throw new LogicException('创建角色失败');
                    }
                }
                if ($aclRoutes) {
                    $userRoleRouteData = [];
                    foreach ($aclRoutes as $route) {
                        if ($userMenuIds && !in_array($route['menuId'], $userMenuIds)) {
                            continue;
                        }
                        $userRoleRouteData[] = [
                            'userRoleId' => $userRole->getId(),
                            'route' => $route['route'],
                            'key' => $route['propertyKey'],
                            'createdAt' => $date,
                            'updatedAt' => $date,
                        ];
                    }
                    if (!UserRoleRoute::insertOrUpdate($userRoleRouteData, true, ['createdAt'])) {
                        throw new LogicException('创建角色失败');
                    }
                }
            }
            return $userRole;
        });
        $this->cleanRoleMenuCache($userRole->getId());
        return [
            'id' => $userRole->getId(),
            'isSuper' => $userRole->getIsSuper(),
            'reader' => $userRole->getReader(),
            'users' => $userRole->getUsers(),
            'name' => $userRole->getName(),
            'remark' => $userRole->getRemark(),
            'createdAt' => $userRole->getCreatedAt(),
            'updatedAt' => $userRole->getUpdatedAt(),
        ];
    }

    /**
     * 创建或修改角色
     * Author:Robert
     *
     * @param string $name
     * @param array $menuIds
     * @param string $reader
     * @param string $remark
     * @return array
     * @throws Throwable
     */
    public function create(string $name, array $menuIds, string $reader, string $remark = ''): array
    {
        if (!in_array($reader, array_keys(\App\Model\Constant\UserRole::READER_MAP))) {
            throw new LogicException('不存在的reader');
        }
        $userMenuIds = \Swoft::getBean(MenuLogic::class)->getTenantMenu();
        $userRole = UserRole::new();
        $userRole->setIsSuper(0);
        $isSuper = $userRole->getIsSuper() == 1;
        $userRole->setName($name);
        $userRole->setIsDeleted(0);
        $userRole->setUsers(0);
        $userRole->setReader($reader);
        $userRole->setRemark($remark);
        if (!$isSuper) {
            $menuIds = AclRoute::whereIn('menuId', $menuIds)->where('tenantId', 0)->get(['key'])->toArray();
            if (!$menuIds) {
                throw new LogicException('the menu has no route');
            }
            $routeKeys = array_column($menuIds, 'propertyKey');
            $aclRoutes = AclRoute::whereIn('key', $routeKeys)->where('tenantId', 0)->get(['route', 'key', 'menuId'])->toArray();
        } else {
            $aclRoutes = [];
        }
        $date = date('Y-m-d H:i:s');
        /** @var UserRole $userRole */
        $userRole = DB::transaction(function () use ($isSuper, $userRole, $userMenuIds, $aclRoutes, $date) {
            if (!$userRole->save()) {
                throw new LogicException('创建角色失败');
            }
            if (!$isSuper) {
                if ($aclRoutes) {
                    $userRoleRouteData = [];
                    foreach ($aclRoutes as $route) {
                        if ($userMenuIds && !in_array($route['menuId'], $userMenuIds)) {
                            continue;
                        }
                        $userRoleRouteData[] = [
                            'userRoleId' => $userRole->getId(),
                            'route' => $route['route'],
                            'key' => $route['propertyKey'],
                            'createdAt' => $date,
                            'updatedAt' => $date,
                        ];
                    }
                    if (!UserRoleRoute::insertOrUpdate($userRoleRouteData, true, ['createdAt'])) {
                        throw new LogicException('创建角色失败');
                    }
                }
            }
            return $userRole;
        });
        $this->cleanRoleMenuCache($userRole->getId());
        return [
            'id' => $userRole->getId(),
            'isSuper' => $userRole->getIsSuper(),
            'reader' => $userRole->getReader(),
            'users' => $userRole->getUsers(),
            'name' => $userRole->getName(),
            'remark' => $userRole->getRemark(),
            'createdAt' => $userRole->getCreatedAt(),
            'updatedAt' => $userRole->getUpdatedAt(),
        ];
    }

    /**
     * @param int $id
     * @return array
     * @throws LogicException
     * @author Robert
     */
    public function remove(int $id): array
    {
        $userRole = $this->getRoleById($id, ['id', 'reader', 'isSuper', 'name', "users", 'remark', 'createdAt', 'updatedAt']);
        if (!$userRole) {
            throw new LogicException('角色不存在');
        }
        if ($userRole->getIsSuper() == 1) {
            throw new LogicException('超级管理角色无法删除');
        }
        $user = User::where('roleId', $id)->first(['id']);
        if ($user) {
            throw new LogicException('角色下存在账户，请先删除账户');
        }
        DB::transaction(function () use ($id) {
            if (!UserRole::where([['id', '=', $id], ['isDeleted', '=', 0]])->update(['isDeleted' => $id])) {
                throw new LogicException('角色删除失败');
            }
            //            if (!UserRoleRoute::where("userRoleId", $id)->delete()) {
            //                throw new LogicException('角色删除失败');
            //            }
        });
        $this->cleanRoleMenuCache($userRole->getId());
        return $userRole->toArray();
    }

    /**
     * Author:Robert
     *
     * @param array $filter
     * @param array|string[] $columns
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function list(array $filter, array $columns = ['*'], int $page = 1, int $pageSize = 20): array
    {
        $where = [['isDeleted', 0]];
        if (isset($filter['id']) && ctype_digit((string)$filter['id'])) {
            $where[] = ['id', $filter['id']];
        }
        if (isset($filter['name']) && $filter['name']) {
            $where[] = ['name', 'LIKE', "%{$filter['name']}%"];
        }
        return [
            'count' => UserRole::where($where)->count(),
            'list' => UserRole::where($where)->forPage($page, $pageSize)->get($columns)->toArray(),
        ];
    }

    /**
     * Author:Robert
     *
     * @param int $id
     * @param array|string[] $columns
     * @param bool $withRoutes
     * @return array
     * @throws LogicException
     */
    public function info(int $id, array $columns = ['*'], bool $withRoutes = false): array
    {
        $userRole = $this->getRoleById($id, $columns);
        if (!$userRole) {
            throw new LogicException('角色不存在');
        }
        $data = $userRole->toArray();
        $data['roleMenus'] = [];
        if ($withRoutes) {
            $data['roleMenus'] = $this->userRoleMenus($id);
            //兼容前端，把权限节点的根节点也放入
            foreach ($data['roleMenus'] as $id) {
                $parentId = Menu::where([['id', $id], ['tenantId', 0]])->value('parentId');
                if ($parentId && !in_array($parentId, $data['roleMenus'])) {
                    $data['roleMenus'][] = $parentId;
                }
            }

        }
        return $data;
    }

    /**
     * Author:Robert
     *
     * @param int $roleId
     * @param int $tenantId
     * @return string
     */
    private function generateRoleMenuCacheKey(int $roleId, int $tenantId): string
    {
        return 'userRoleMenu:'.$tenantId.':'.$roleId;
    }


    /**
     * Author:Robert
     *
     * @param int $roleId
     * @return bool
     */
    public function increaseUsers(int $roleId): bool
    {
        return UserRole::where([['id', $roleId], ['isDeleted', 0]])->increment('users', 1) > 0;
    }

    /**
     * Author:Robert
     *
     * @param int $roleId
     * @return bool
     */
    public function decreaseUsers(int $roleId): bool
    {
        return UserRole::where([['id', $roleId], ['isDeleted', 0]])->decrement('users', 1) > 0;
    }

    /**
     * Author:Robert
     *
     * @param int $roleId
     * @param int|null $tenantId
     * @return array|null
     */
    public function getRoleMenuCache(int $roleId, int $tenantId = null): ?array
    {
        $tenantId = $tenantId ?: currentTenantId();
        $cache = Redis::get($this->generateRoleMenuCacheKey($roleId, $tenantId)) ?: null;
        return $cache ? unserialize($cache) : $cache;
    }

    /**
     * Author:Robert
     *
     * @param int $roleId
     * @param array $menuIds
     * @param int $ttl
     * @param int|null $tenantId
     */
    public function setRoleMenuCache(int $roleId, array $menuIds, int $ttl = 86400, int $tenantId = null)
    {
        $tenantId = $tenantId ?: currentTenantId();
        Redis::setex($this->generateRoleMenuCacheKey($roleId, $tenantId), $ttl, serialize($menuIds));
    }

    /**
     * Author:Robert
     *
     * @param int $roleId
     * @param int|null $tenantId
     * @return int
     */
    public function cleanRoleMenuCache(int $roleId, int $tenantId = null): int
    {
        $tenantId = $tenantId ?: currentTenantId();
        return Redis::del($this->generateRoleMenuCacheKey($roleId, $tenantId));
    }

    /**
     * 角色对应拥有的栏目ID
     * Author:Robert
     *
     * @param int $roleId
     * @return array
     */
    public function userRoleMenus(int $roleId): array
    {
        $cache = $this->getRoleMenuCache($roleId);
        if ($cache) {
            return $cache;
        }
        $sql = "SELECT `acl_route`.menuId
                  FROM `user_role_route`
            INNER JOIN `acl_route`
                    ON `user_role_route`.`key` = acl_route.key
                   AND user_role_route.userRoleId= ?";
        $result = DB::select($sql, [$roleId]);
        $menus = array_column($result, 'menuId');
        $menus = arrayUnique($menus);
        $this->setRoleMenuCache($roleId, $menus, 28800);
        return $menus;
    }


    /**
     * Author:Robert
     *
     * @param int $id
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return object|\Swoft\Db\Eloquent\Builder|\Swoft\Db\Eloquent\Model|null
     */
    public function getRoleById(int $id, array $columns = ['*'], bool $withDeleted = false)
    {
        $where = [
            ['id', $id],
        ];
        if (!$withDeleted) {
            $where[] = ['isDeleted', 0];
        }
        return UserRole::where($where)->first($columns);
    }
}
