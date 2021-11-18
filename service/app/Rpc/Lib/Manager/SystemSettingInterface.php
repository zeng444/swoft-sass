<?php declare(strict_types=1);


namespace App\Rpc\Lib\Manager;

/**
 * Author:Robert
 *
 * Class SystemSettingInterface
 * @package App\Rpc\Lib\Manager
 */
interface SystemSettingInterface
{

    /**
     * Author:Robert
     *
     * @param int $tenantId
     * @return array
     */
    public function getAll(int $tenantId): array;

    /**
     * Author:Robert
     *
     * @param string $key
     * @param string $val
     * @param int|null $tenantId
     * @return bool
     */
    public function set(string $key, string $val, int $tenantId = null): bool;

}
