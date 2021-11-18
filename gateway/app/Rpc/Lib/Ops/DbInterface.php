<?php

namespace App\Rpc\Lib\Ops;

/**
 * Author:Robert
 *
 * Interface DbInterface
 * @package App\Rpc\Lib\Ops
 */
interface DbInterface
{

    public function statement(string $sql, array $bind = []): array;

}
