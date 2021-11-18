<?php declare(strict_types=1);

namespace App\Rpc\Service\Tenant;

use App\Model\Logic\Tenant\AclRouteLogic;
use App\Rpc\Lib\Tenant\AclRouteInterface;
use Swoft\Rpc\Server\Annotation\Mapping\Service;
use Throwable;

/**
 * Class AclRouteService
 * @Service()
 * @author Robert
 * @package App\Rpc\Service
 */
class AclRouteService implements AclRouteInterface
{
    /**
     * @param array $routes
     * @return bool
     * @author Robert
     * @throws Throwable
     */
    public function build(array $routes): bool
    {
        /** @var AclRouteLogic $aclRouteLogic */
        $aclRouteLogic = \Swoft::getBean(AclRouteLogic::class);
        return $aclRouteLogic->build($routes);
    }

    /**
     * @param array $routes
     * @return bool
     * @author Robert
     * @throws Throwable
     */
    public function rebuild(array $routes): bool
    {
        return $this->build($routes);

    }

}
