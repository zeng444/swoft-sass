<?php declare(strict_types=1);

namespace App\Rpc\Lib\Tenant;

/**
 * Author:Robert
 *
 * Interface UserInterface
 * @package App\Rpc\Lib
 */
interface UserInterface
{
    /**
     * @param string $account
     * @param string $password
     * @return array
     * @author Robert
     */
    public function authorize(string $account, string $password): array;


    /**
     * Author:Robert
     *
     * @param string $account
     * @param string $password
     * @param string $nickname
     * @param string $mobile
     * @param int $roleId
     * @param int|null $groupId
     * @return array
     */
    public function register(
        string $account,
        string $password,
        string $nickname,
        string $mobile,
        int $roleId,
        int $groupId = null
    ): array;

    /**
     * Author:Robert
     *
     * @param int $id
     * @param string|null $account
     * @param string|null $password
     * @param string|null $nickname
     * @param string|null $mobile
     * @param int|null $roleId
     * @param int|null $groupId
     * @param array $orderAccountIds
     * @return array
     */
    public function edit(
        int $id,
        string $account = null,
        string $password = null,
        string $nickname = null,
        string $mobile = null,
        int $roleId = null,
        int $groupId = null
    ): array;

    /**
     * Author:Robert
     *
     * @param array $userId
     * @param bool $status
     * @return int
     */
    public function switchStatus(array $userId, bool $status): int;

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
     * Author:Robert
     *
     * @param int $userId
     * @return array
     */
    public function remove(int $userId): array;

    /**
     * Author:Robert
     *
     * @param int $userId
     * @param array|string[] $columns
     * @return array
     */
    public function info(int $userId, array $columns = ['*']): array;


    /**
     * Author:Robert
     *
     * @param int $userId
     * @return array
     */
    public function permissions(int $userId): array;


    /**
     * Author:Robert
     *
     * @param int $userId
     * @param string $currentRoute
     * @return bool
     */
    public function allowedRoutes(int $userId, string $currentRoute): bool;
}
