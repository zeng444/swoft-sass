<?php declare(strict_types=1);
/**
 * Author:Robert
 *
 */

namespace App\Rpc\Client\Contract;

use App\Model\Logic\ServerLogic;
use App\Model\Logic\ServiceLogic;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Client\Contract\ProviderInterface;
use Swoft\Rpc\Client\Client;

/**
 * Author:Robert
 * @Bean()
 * Class RpcProvider
 * @package App\Rpc\Client\Contract
 */
class RpcProvider implements ProviderInterface
{

    public function getList(Client $client): array
    {
        $server = (\Swoft::getBean(ServerLogic::class))->getCurrentServer();
        /** @var ServiceLogic $serviceLogic */
        $serviceLogic = \Swoft::getBean(ServiceLogic::class);
        $list = $serviceLogic->getList($server['id']);
        $host = [];
        foreach ($list as $item) {
            $host[$item['code']] = $item['host'];
        }
        return $host;
    }
}
