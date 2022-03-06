<?php declare(strict_types=1);

namespace App\Rpc\Service\Tenant;

use App\Exception\LogicException;
use App\Model\Logic\Tenant\UserLogic;
use App\Rpc\Lib\Tenant\UserInterface;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Rpc\Server\Annotation\Mapping\Service;
use Throwable;

/**
 * Author:Robert
 *
 * Class UserService
 * @Service()
 * @package App\Rpc\Service
 */
class UserService implements UserInterface
{

    /**
     * @Inject()
     * @var UserLogic
     */
    protected $userLogic;

    /**
     * @param array $filter
     * @param array|string[] $columns
     * @param int $page
     * @param int $pageSize
     * @return array
     * @author Robert
     */
    public function list(array $filter, array $columns = ['*'], int $page = 1, int $pageSize = 20): array
    {
        return $this->userLogic->list($filter, $columns, $page);
    }

    /**
     * 登录验证
     * Author:Robert
     *
     * @param string $account
     * @param string $password
     * @return array
     * @throws Throwable
     */
    public function authorize(string $account, string $password): array
    {
        return $this->userLogic->authorize($account, $password);
    }


    /**
     * @param string $account
     * @param string $password
     * @param string $nickname
     * @param string $mobile
     * @param int $roleId
     * @param int|null $groupId
     * @return array
     * @throws Throwable
     * @author Robert
     */
    public function register(
        string $account,
        string $password,
        string $nickname,
        string $mobile,
        int $roleId,
        int $groupId = null
    ): array {
        return $this->userLogic->register($account, $password, $nickname, $mobile, $roleId, $groupId);
    }


    /**
     * Author:Robert
     *
     * @param int $userId
     * @return array
     * @throws Throwable
     */
    public function remove(int $userId): array
    {
        return $this->userLogic->remove($userId);
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
     * @throws LogicException
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
        return $this->userLogic->edit($id, $account, $password, $nickname, $mobile, $roleId, $groupId);
    }

    /**
     * 切换用户状态
     *
     * @param array $userId
     * @param bool $status
     * @return int
     */
    public function switchStatus(array $userId, bool $status): int
    {
        return $this->userLogic->switchStatus($userId, $status);
    }

    /**
     * Author:Robert
     *
     * @param int $userId
     * @param array|string[] $columns
     * @return array
     * @throws LogicException
     */
    public function info(int $userId, array $columns = ['*']): array
    {
        return $this->userLogic->info($userId, $columns);
    }

    /**
     * Author:Robert
     *
     * @param int $userId
     * @return array
     * @throws LogicException
     */
    public function permissions(int $userId): array
    {
        /** @var UserLogic $userLogic */
        $userLogic = \Swoft::getBean(UserLogic::class);
        return $userLogic->permissions($userId);
    }


    /**
     * Author:Robert
     *
     * @param int $userId
     * @param string $currentRoute
     * @return bool
     * @throws LogicException
     */
    public function allowedRoutes(int $userId, string $currentRoute): bool
    {
        return $this->userLogic->allowedRoutes($userId, $currentRoute);
    }
}
