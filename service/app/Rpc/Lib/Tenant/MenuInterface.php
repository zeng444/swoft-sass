<?php declare(strict_types=1);

namespace App\Rpc\Lib\Tenant;

/**
 * Author:Robert
 *
 * Interface MenuInterface
 * @package App\Rpc\Lib
 */
interface MenuInterface
{
    /**
     * @param int $userId
     * @return array
     * @author Robert
     */
    public function tree(int $userId): array;

    /**
     * @return array
     * @author Robert
     */
    public function systemTree(): array;
}
