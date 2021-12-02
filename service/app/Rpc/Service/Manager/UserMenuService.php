<?php declare(strict_types=1);

namespace App\Rpc\Service\Manager;

use App\Model\Logic\Manager\UserMenuLogic;
use App\Rpc\Lib\Manager\UserMenuInterface;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Rpc\Server\Annotation\Mapping\Service;
use Throwable;

/**
 * Author:Robert
 *
 * Class UserMenuService
 * @Service()
 * @package App\Rpc\Service\Manager
 */
class UserMenuService implements UserMenuInterface
{

    /**
     * @Inject()
     * @var UserMenuLogic
     */
    protected $userMenuLogic;

    /**
     * @param int $tenantId
     * @param array $menuIds
     * @return bool
     * @author Robert
     */
    public function setMenu(int $tenantId, array $menuIds): bool
    {
        return $this->userMenuLogic->setMenu($tenantId, $menuIds);
    }

    /**
     * @param int $tenantId
     * @return array
     * @author Robert
     */
    public function menus(int $tenantId): array
    {
        return $this->userMenuLogic->menus($tenantId);
    }
}
