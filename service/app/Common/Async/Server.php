<?php declare(strict_types=1);

namespace App\Common\Async;

use App\Common\Db\DbSelector;
use App\Common\Db\MySqlConnection;
use Pheanstalk\Pheanstalk;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Config\Annotation\Mapping\Config;
use Pheanstalk\Exception\DeadlineSoonException;
use Swoft\Log\Helper\CLog;
use App\Common\Caller\Client as CallerClient;
use Swoole\Process\Pool;
use Throwable;

/**
 * Class Server
 * @author Robert
 * @Bean()
 * @package App\Common\Caller
 */
class Server
{

    /**
     * @Config("caller.tube")
     * @var string
     */
    protected $tube = 'swoftcall626';

    /**
     * @Config("caller.host")
     * @var string
     */
    protected $host = 'beanstalkd';

    /**
     * @Config("caller.port")
     * @var int
     */
    protected $port = 11300;

    /**
     * @Config("caller.maxRequest")
     * @var int
     */
    protected $maxRequest = 2000;

    /**
     * Author:Robert
     *
     * @return Pheanstalk
     */
    private function connect(): Pheanstalk
    {
        return Pheanstalk::create($this->host, $this->port);
    }

    /**
     * @param string $msg
     * @param string $type
     * @return void
     * @author Robert
     */
    public static function log(string $msg, string $type = "error"): void
    {
        CLog::$type($msg);
    }

    /**
     * @author Robert
     */
    public function handle(Pool $pool, int $workerId)
    {
        $connection = $this->connect();
        $connection->watchOnly($this->tube);
        $workIdTag = "[workerId:$workerId] ";
        $tasked = 0;
        while (true) {
            try {
                $job = $connection->reserveWithTimeout(5500);
//                $job = $connection->reserve();
                if (!$job) {
                    self::log('Timeout Restart','info');
                    break;
                }
                $data = $job->getData();
                if (!$data || !$data = unserialize($data)) {
                    $connection->bury($job);
                    self::log($workIdTag . 'data is empty');
                    continue;
                }
                $connection->delete($job);
                $tasked++;
                self::log("task text:" . json_encode($data),'info');
                list($status, $result, $msg) = $this->consumer($data);
                if (!$status) {
                    //                    $connection->bury($job);
                    self::log($msg);
                }
                self::log($workIdTag . 'result:' . json_encode($result), 'info');
                if ($tasked >= $this->maxRequest) {
                    self::log('Max Request Restart','info');
                    break;
                }
            } catch (DeadlineSoonException $e) {
                CLog::info($e->getMessage().$e->getTraceAsString());
                self::log($workIdTag . 'deadline soon ' . $e->getMessage());
                break;
            } catch (\Exception $e) {
                //                $connection->bury($job);
                //                $connection->release($job, PheanstalkInterface::DEFAULT_PRIORITY * 100);
                CLog::info($e->getMessage().$e->getTraceAsString());
                self::log($workIdTag . $e->getMessage());
                break;
            }
        }
    }

    /**
     * @param array $data
     * @return array
     * @author Robert
     */
    protected function consumer(array $data): array
    {
        if (!isset($data['ext']) || !$data['ext'] || !isset($data['ext']['db']) || !$data['ext']['db'] || !isset($data['ext']['tenantId']) || !$data['ext']['tenantId']) {
            return [false, null, 'ext is empty'];
        }
        if (!isset($data['className']) || !$data['className'] || !isset($data['methodName']) || !$data['methodName'] || !isset($data['params'])) {
            return [false, null, 'className or methodName or params is empty'];
        }
        if (!$data['params'] || !is_array($data['params'])) {
            return [false, null, 'params is empty'];
        }
        if (class_exists($data['className']) === false) {
            return [false, null, 'className ' . $data['className'] . ' not exist'];
        }
        $ext = $data['ext'];
        $tenantId = $ext['tenantId'] ?? '0';
        $db = $ext['db'] ?? '';
        if (!$db) {
            return [false, null, 'MySqlConnection has no db'];
        }
        \Swoft::getBean(DbSelector::class)->setDBName($db);
        if ($tenantId > 0) {
            \Swoft::getBean(MySqlConnection::class)->setTenantId((string)($tenantId));
        }
        try {
            return [true, CallerClient::call($data['className'], $data['methodName'], $data['params']), ''];
        } catch (Throwable $e) {
            return [false, null, env('APP_ENV') === 'DEV' ? $e->getMessage() . $e->getTraceAsString() : $e->getMessage()];
        }
    }

}
