<?php declare(strict_types=1);

namespace App\Model\Logic;

use App\Rpc\Lib\Tenant\UserRoleInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;

/**
 * Author:Robert
 *
 * Class UserRoleLogic
 * @Bean()
 * @package App\Model\Logic
 */
class UserRoleLogic
{


    /**
     * @Reference("biz.pool")
     * @var UserRoleInterface
     */
    protected $userRoleService;

    /**
     * Author:Robert
     *
     * @param string $name
     * @param array $menuIds
     * @param string $reader
     * @param string $remark
     * @return array
     */
    public function create(string $name, array $menuIds, string $reader = '', string $remark = ''): array
    {
        return $this->userRoleService->create($name, $menuIds, $reader, $remark);
    }


    /**
     * Author:Robert
     *
     * @param int $roleId
     * @param string $name
     * @param array $menuIds
     * @param string $reader
     * @param string $remark
     * @return array
     */
    public function edit(
        int $roleId,
        string $name,
        array $menuIds,
        string $reader = '',
        string $remark = ''
    ): array {
        return $this->userRoleService->edit($roleId, $name, $menuIds, $reader, $remark);
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
        return $this->userRoleService->list($filter, $columns, $page, $pageSize);
    }

    /**
     * @param int $id
     * @return array
     * @author Robert
     */
    public function remove(int $id): array
    {
        return $this->userRoleService->remove($id);
    }


    /**
     * Author:Robert
     *
     * @param int $id
     * @param array|string[] $columns
     * @param bool $withRoutes
     * @return array
     */
    public function info(int $id, array $columns = ['*'], bool $withRoutes = false): array
    {
        return $this->userRoleService->info($id, $columns, $withRoutes);
    }
}
