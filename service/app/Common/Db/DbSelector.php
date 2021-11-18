<?php declare(strict_types=1);

namespace App\Common\Db;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\Connection\Connection;
use Swoft\Db\Contract\DbSelectorInterface;

/**
 * Author:Robert
 *
 * Class DbSelector
 * @Bean()
 * @package App\Common\Db
 */
class DbSelector implements DbSelectorInterface
{
    const DB_NAME = 'DB_SELECTOR_KEY';

    /**
     * @param Connection $connection
     */
    public function select(Connection $connection): void
    {
        $dbName = $this->getDBName();
        if ($dbName) {
            $connection->db($dbName);
        }
    }

    /**
     * @param string $name
     * @author Robert
     */
    public function setDBName(string $name): void
    {
        context()->set(self::DB_NAME, $name);
    }

    /**
     * @return string
     * @author Robert
     */
    public function getDBName(): string
    {
        return (string)context()->get(self::DB_NAME);
    }
}
