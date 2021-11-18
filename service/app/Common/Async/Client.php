<?php declare(strict_types=1);

namespace App\Common\Async;

use App\Common\Db\DbSelector;
use App\Common\Db\MySqlConnection;
use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Pheanstalk;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Config\Annotation\Mapping\Config;

/**
 * Class Client
 * @author Robert
 * @Bean()
 * @package App\Common\Caller
 */
class Client
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
     * Author:Robert
     *
     * @return Pheanstalk
     */
    private function connect(): Pheanstalk
    {
        return Pheanstalk::create($this->host, $this->port);
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param array $params
     * @param int $delay
     * @return string
     * @author Robert
     */
    public function push(string $className, string $methodName, array $params = [], int $delay = 0): string
    {
        $data = [
            'className' => $className,
            'methodName' => $methodName,
            'params' => $params,
            'ext' => $this->attachExt()
        ];
        $connection = $this->connect();
        $connection->useTube($this->tube);
        $job = $connection->put(serialize($data), PheanstalkInterface::DEFAULT_PRIORITY, $delay, 60);
        return (string)$job->getId();
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param array $params
     * @param int $delay
     * @return string
     * @author Robert
     */
    public static function async(string $className, string $methodName, array $params = [], int $delay = 0): string
    {
        return \Swoft::getBean(self::class)->push($className, $methodName, $params, $delay);
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
}
