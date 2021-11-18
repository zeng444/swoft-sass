<?php
/**
 * Author:Robert
 *
 */

namespace App\Rpc\Client;

use Swoft\Rpc\Client\Connection;
//use App\Rpc\Client\Connection;
use Swoft\Rpc\Client\Exception\RpcClientException;
use Swoft\Rpc\Client\Pool;

/**
 * Author:Robert
 *
 * Class Client
 * @package App\Rpc\Client
 */
class Client extends \Swoft\Rpc\Client\Client
{
    /**
     * @param Pool $pool
     *
     * @return Connection
     * @throws RpcClientException
     */
    public function createConnection(Pool $pool): Connection
    {
        $connection = Connection::new($this, $pool);
        $connection->create();
        return $connection;
    }

}
