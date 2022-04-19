<?php declare(strict_types=1);

namespace App\Common\Db;

use App\Common\Db\SQL\ParserInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DbEvent;
use Closure;

/**
 * Class MySqlConnector
 * @Bean(scope=Bean::PROTOTYPE)
 * @author Robert
 * @package App\Common\Db
 */
class MySqlConnection extends \Swoft\Db\Connection\MySqlConnection
{

    const CONTEXT_TENANT_ID = 'TENANT_ID';


    /**
     * @param string $query
     * @param array $bindings
     * @param Closure $callback
     * @return mixed
     * @author Robert
     */
    protected function run(string $query, array $bindings, Closure $callback)
    {
        $this->reconnectIfMissingConnection();
        $start = microtime(true);
        list($query, $bindings) = (\Swoft::getBean(ParserInterface::class))->process($query, $bindings, $this->getTenantId());
        // Here we will run this query. If an exception occurs we'll determine if it was
        // caused by a connection that has been lost. If that is the cause, we'll try
        $result = $this->runQueryCallback($query, $bindings, $callback);
        $time = $this->getElapsedTime($start);
        $this->fireEvent(DbEvent::SQL_RAN, $query, $bindings, $time);

        // Once we have run the query we will calculate the time that it took to run and
        // then log the query, bindings, and execution time so we will report them on
        // the event that the developer needs them. We'll log time in milliseconds.
        return $result;
    }


    /**
     * @param string $tenantId
     * @author Robert
     */
    public function setTenantId(string $tenantId): void
    {
        context()->set(self::CONTEXT_TENANT_ID, $tenantId);
    }

    /**
     * @return int
     * @author Robert
     */
    public function getTenantId(): int
    {
        return intval(\context()->get(self::CONTEXT_TENANT_ID));
    }

    /**
     * 不适用注入
     * @param Closure $callback
     * @return mixed|void
     */
    public static function noTenant(Closure $callback)
    {
        /** @var MySqlConnection $mySqlConnection */
        $mySqlConnection = \Swoft::getBean(MySqlConnection::class);
        $currentTenantId = (string)$mySqlConnection->getTenantId();
        try {
            $mySqlConnection->setTenantId('');
            $result = $callback($currentTenantId);
            $mySqlConnection->setTenantId($currentTenantId);
            return $result;
        }catch (\Exception $exception){
            $mySqlConnection->setTenantId($currentTenantId);
        }
    }
}
