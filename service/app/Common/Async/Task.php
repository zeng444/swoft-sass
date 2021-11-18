<?php declare(strict_types=1);


namespace App\Common\Async;

use App\Common\Db\DbSelector;
use App\Common\Db\MySqlConnection;
use App\Common\Caller\Client as CallerClient;
use SebastianBergmann\Timer\RuntimeException;

/**
 * Class Async
 * @author Robert
 * @package App\Common\Async
 */
class Task
{

    /**
     * @param string $className
     * @param string $methodName
     * @param array $params
     * @return mixed
     * @author Robert
     * @throws RuntimeException
     */
    public static function call(string $className, string $methodName, array $params = [])
    {
        $request = context()->getRequest();
        $ext = $request->getExt();
        $tenantId = $ext['tenantId'] ?? '0';
        $db = $ext['db'] ?? '';
        if (!$db) {
            throw new \RuntimeException('MySqlConnection has no db');
        }
        \Swoft::getBean(DbSelector::class)->setDBName($db);
        if ($tenantId > 0) {
            \Swoft::getBean(MySqlConnection::class)->setTenantId((string)($tenantId));
        }
        return CallerClient::call($className,$methodName,$params);
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param array $params
     * @param float|int $timeout
     * @param array $ext
     * @return mixed
     * @throws \Swoft\Task\Exception\TaskException
     * @author Robert
     */
    public static function co(string $className, string $methodName, array $params = [], float $timeout = 3, array $ext = [])
    {
        return \Swoft\Task\Task::co('async', 'call', [
            $className,
            $methodName,
            $params,
        ], $timeout, array_merge($ext, self::attachExt()));
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param array $params
     * @param array $ext
     * @param int $dstWorkerId
     * @param callable|null $fallback
     * @return string
     * @throws \Swoft\Task\Exception\TaskException
     * @author Robert
     */
    public static function async(string $className, string $methodName, array $params = [], array $ext = [], int $dstWorkerId = -1, callable $fallback = null): string
    {
        return \Swoft\Task\Task::async('async', 'call', [
            $className,
            $methodName,
            $params,
        ], array_merge($ext, self::attachExt()), $dstWorkerId, $fallback);
    }

    /**
     * @return array
     * @author Robert
     */
    protected static function attachExt(): array
    {
        return [
            'tenantId' => \Swoft::getBean(MySqlConnection::class)->getTenantId(),
            'db' => \Swoft::getBean(DbSelector::class)->getDBName()
        ];
    }

    /**
     * @param array $calls
     * @param float|int $timeout
     * @param array $ext
     * @return array
     * @throws \Swoft\Task\Exception\TaskException
     * @author Robert
     */
    public static function cos(array $calls, float $timeout = 3, array $ext = [])
    {
        $tasks = [];
        foreach ($calls as $call) {
            $tasks[] = [
                'async',
                'call',
                $call
            ];
        }
        return \Swoft\Task\Task::cos($tasks, $timeout, array_merge($ext, self::attachExt()));
    }

}
