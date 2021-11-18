<?php declare(strict_types=1);

namespace App\Common\Memory;

use Closure;

/**
 * Author:Robert
 *
 * Class Handle
 * @package App\Common\Memory
 */
class Handle
{

    /**
     * Author:Robert
     *
     * @param Closure $callable
     * @param string $memory
     * @return mixed
     */
    public static function run(Closure $callable, string $memory = '180M')
    {
        $limit = ini_get('memory_limit');
        try {
            ini_set('memory_limit', $memory);
            $result = $callable($limit, $memory);
            ini_set('memory_limit', $limit);
            return $result;
        } catch (\Throwable $exception) {
            ini_set('memory_limit', $limit);
            throw $exception;
        }
    }
}
