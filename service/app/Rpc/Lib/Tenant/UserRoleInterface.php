<?php declare(strict_types=1);

namespace App\Rpc\Lib\Tenant;

/**
 * Author:Robert
 *
 * Interface UserRoleInterface
 * @package App\Rpc\Lib
 */
interface UserRoleInterface
{
    /**
     * Author:Robert
     *
     * @param string $name
     * @param array $menuIds
     * @param string $reader
     * @param string $remark
     * @return array
     */
    public function create(string $name, array $menuIds, string $reader, string $remark = ''): array;


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
    public function edit(int $roleId, string $name, array $menuIds, string $reader, string $remark = ''): array;

    /**
     * Author:Robert
     *
     * @param array $filter
     * @param array|string[] $columns
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function list(array $filter, array $columns = ['*'], int $page = 1, int $pageSize = 20): array;

    /**
     * @param int $id
     * @return array
     * @author Robert
     */
    public function remove(int $id): array;


    /**
     * Author:Robert
     *
     * @param int $id
     * @param array|string[] $columns
     * @param bool $withRoutes
     * @return array
     */
    public function info(int $id, array $columns = ['*'], bool $withRoutes = false): array;
}
