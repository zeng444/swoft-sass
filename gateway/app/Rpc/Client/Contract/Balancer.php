<?php declare(strict_types=1);

namespace App\Rpc\Client\Contract;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Client\Contract\BalancerInterface;

/**
 * Author:Robert
 * @Bean()
 */
class Balancer implements BalancerInterface
{
    const RPC_CONNECTION_NAME = 'rpcBizHost';

    /**
     * @param array $hosts
     * @return false|mixed
     * @author Robert
     */
    public function handle(array $hosts)
    {
        $key = $this->getHostName();
        if (!$key) {
            return current($hosts);
        }
        return $hosts[$key] ?? current($hosts);
    }

    /**
     * @param string $name
     * @author Robert
     */
    public function setHostName(string $name): void
    {
        context()->set(self::RPC_CONNECTION_NAME, $name);
    }

    /**
     * @return string
     * @author Robert
     */
    public function getHostName(): ?string
    {
        return context()->get(self::RPC_CONNECTION_NAME);
    }
}
