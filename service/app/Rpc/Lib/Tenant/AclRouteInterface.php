<?php declare(strict_types=1);


namespace App\Rpc\Lib\Tenant;

/**
 * Interface AclRouteInterface
 * @author Robert
 * @package App\Rpc\Lib
 */
interface AclRouteInterface
{
    /**
     * @param array $routes
     * @return bool
     * @author Robert
     */
    public function build(array $routes): bool;

    /**
     * @param array $routes
     * @return bool
     * @author Robert
     */
    public function rebuild(array $routes): bool;

}
