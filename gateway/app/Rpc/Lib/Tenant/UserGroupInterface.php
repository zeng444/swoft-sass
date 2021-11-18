<?php declare(strict_types=1);

namespace App\Rpc\Lib\Tenant;

/**
 * Author:Robert
 *
 * Interface UserGroupInterface
 * @package App\Rpc\Lib
 */
interface UserGroupInterface
{
    /**
     * Author:Robert
     *
     * @param int|null $id
     * @param string $name
     * @return array
     */
    public function touch(?int $id, string $name): array;

    /**
     * Author:Robert
     *
     * @param int $id
     * @return array
     */
    public function remove(int $id): array;

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
}
