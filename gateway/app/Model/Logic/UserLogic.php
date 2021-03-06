<?php declare(strict_types=1);

namespace App\Model\Logic;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;
use App\Rpc\Lib\Tenant\UserInterface;
use Throwable;

/**
 * Author:Robert
 *
 * Class UserLogic
 * @Bean()
 * @package App\Model\Logic
 */
class UserLogic
{

    /**
     * @Reference(pool="biz.pool")
     * @var UserInterface
     */
    public $userService;

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
     * @throws Throwable
     */
    public function register(
        string $account,
        string $password,
        string $nickname,
        string $mobile,
        int $roleId,
        int $groupId = null
    ): array {
        return $this->userService->register($account, $password, $nickname, $mobile, $roleId, $groupId);
    }

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
    ): array {
        return $this->userService->edit($id, $account, $password, $nickname, $mobile, $roleId, $groupId);
    }

    /**
     * 切换账户状态
     * Author:Robert
     *
     * @param array $userIds
     * @param bool $status
     * @return int
     */
    public function switchStatus(array $userIds, bool $status): int
    {
        return $this->userService->switchStatus($userIds, $status);
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
        return $this->userService->list($filter, $columns, $page, $pageSize);
    }


    /**
     * Author:Robert
     *
     * @param int $userId
     * @return array
     */
    public function remove(int $userId): array
    {
        return $this->userService->remove($userId);
    }

    /**
     * Author:Robert
     *
     * @param int $userId
     * @param array|string[] $columns
     * @return array
     */
    public function info(int $userId, array $columns = ['*']): array
    {
        return $this->userService->info($userId, $columns);
    }

    /**
     * Author:Robert
     *
     * @param int $userId
     * @return array
     */
    public function permissions(int $userId): array
    {
        return $this->userService->permissions($userId);
    }

}
