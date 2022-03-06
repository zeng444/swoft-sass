<?php declare(strict_types=1);


namespace App\Rpc\Lib\Manager;

/**
 * Author:Robert
 *
 * Interface BrandInterface
 * @package App\Rpc\Lib\Manager
 */
interface UserInterface
{

    /**
     * @param int $tenantId
     * @param string $password
     * @return int
     * @author Robert
     */
    public function resetSuperPassword(int $tenantId, string $password = ''): int;

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
     */
    public function registerSuper(
        int $tenantId,
        string $account,
        string $password,
        string $mobile = '',
        string $remark = '',
        array $config = []
    ): array;

    /**
     * Author:Robert
     *
     * @param int $tenantId
     * @param array|string[] $columns
     * @return array
     */
    public function superAdminInfo(int $tenantId, array $columns=['*']): array;

}
