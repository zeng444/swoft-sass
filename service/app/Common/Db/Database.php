<?php declare(strict_types=1);

namespace App\Common\Db;

/**
 * Author:Robert
 *
 * Class Database
 * @package App\Common\Db
 */
class Database extends \Swoft\Db\Database
{

    /**
     * @return array
     */
    public function defaultConnections(): array
    {
        return [
            self::MYSQL => bean(MySqlConnection::class),
        ];
    }
}
