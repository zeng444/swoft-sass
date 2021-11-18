<?php declare(strict_types=1);

namespace App\Rpc\Service\Tenant;

use App\Model\Logic\Tenant\MenuLogic;
use App\Rpc\Lib\Tenant\MenuInterface;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Rpc\Server\Annotation\Mapping\Service;
use Throwable;

/**
 * Author:Robert
 *
 * Class MenuService1
 * @Service()
 * @package App\Rpc\Service
 */
class MenuService implements MenuInterface
{
    /**
     * @Inject()
     * @var MenuLogic
     */
    protected $menuLogic;

    /**
     * @param int $userId
     * @return array
     * @throws Throwable
     * @author Robert
     */
    public function tree(int $userId): array
    {
        return $this->menuLogic->tree($userId);
    }

    /**
     * @return array
     * @author Robert
     */
    public function systemTree(): array
    {
        return $this->menuLogic->systemTree();
    }

}
