<?php declare(strict_types=1);

namespace App\Rpc\Lib\Manager;

/**
 * Interface UserMenuInterface
 * @author Robert
 * @package App\Rpc\Lib\Manager
 */
interface UserMenuInterface
{
    /**
     * @param int $tenantId
     * @param array $menuIds
     * @return bool
     * @author Robert
     */
    public function setMenu(int $tenantId, array $menuIds): bool;

    /**
     * @param int $tenantId
     * @return array
     * @author Robert
     */
    public function menus(int $tenantId): array;
}
