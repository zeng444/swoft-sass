<?php declare(strict_types=1);

namespace App\Rpc\Service\Manager;

use App\Exception\LogicException;
use App\Model\Logic\Manager\UserLogic;
use App\Rpc\Lib\Manager\UserInterface;
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
     * @param int $tenantId
     * @param string $password
     * @return int
     * @author Robert
     */
    public function resetSuperPassword(int $tenantId, string $password = ''): int
    {
        return $this->userLogic->resetSuperPassword($tenantId, $password);
    }

    /**
     * Author:Robert
     *
     * @param int $tenantId
     * @param string $account
     * @param string $password
     * @param string $mobile
     * @param string $remark
     * @param array $config
     * @return array
     * @throws Throwable
     */
    public function registerSuper(
        int $tenantId,
        string $account,
        string $password,
        string $mobile = '',
        string $remark = '',
        array $config = []
    ): array {
        return $this->userLogic->registerSuper($tenantId, $account, $password, $mobile, $remark, $config);
    }

    /**
     * Author:Robert
     *
     * @param int $tenantId
     * @param array|string[] $columns
     * @return array
     */
    public function superAdminInfo(int $tenantId, array $columns=['*']): array
    {
        return $this->userLogic->superAdminInfo($tenantId, $columns);
    }

}
