<?php
/**
 * Author:Robert
 *
 */

namespace App\Rpc\Client;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Client\Exception\RpcClientException;
use Swoft\Stdlib\Helper\JsonHelper;
use function sprintf;

/**
 * Class Connection
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Connection extends \Swoft\Rpc\Client\Connection
{

    /**
     * @return array
     * @throws RpcClientException
     */
    private function getHostPort(): array
    {
        $provider = $this->client->getProvider();
        if (!$provider) {
            return [$this->client->getHost(), $this->client->getPort()];
        }

        $list = $provider->getList($this->client);
        if (!$list) {
            throw new RpcClientException(sprintf('Provider return list can not empty!'));
        }

        if (!is_array($list)) {
            throw new RpcClientException(sprintf('Provider(%s) return format is error!', JsonHelper::encode($list)));
        }

        $randKey  = array_rand($list, 1);
        $hostPort = explode(':', $list[$randKey]);

        if (count($hostPort) < 2) {
            throw new RpcClientException(sprintf('Provider(%s) return format is error!', $list[$randKey]));
        }

        [$host, $port] = $hostPort;
        return [$host, $port];
    }
}
