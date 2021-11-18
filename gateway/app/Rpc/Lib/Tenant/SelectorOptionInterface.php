<?php declare(strict_types=1);

namespace App\Rpc\Lib\Tenant;


/**
 * Author:Robert
 *
 * Interface SelectorOptionInterface
 * @package App\Rpc\Lib
 */
interface SelectorOptionInterface
{

    /**
     * Author:Robert
     *
     * @param array $filter
     * @param string $type
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getOptions(array $filter, string $type, int $page = 1, int $pageSize = 100): array;
}
