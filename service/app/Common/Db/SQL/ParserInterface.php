<?php declare(strict_types=1);

namespace App\Common\Db\SQL;

/**
 * Author:Robert
 *
 * Interface ParserInterface
 * @package App\Common\Db\SQL
 */
interface ParserInterface
{

    /**
     * Author:Robert
     *
     * @param string $query
     * @param array $bindings
     * @param int $tenantId
     * @return array
     */
    public function process(string $query, array $bindings, int $tenantId): array;
}
