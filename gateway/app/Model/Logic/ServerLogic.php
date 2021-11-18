<?php declare(strict_types=1);

namespace App\Model\Logic;

use App\Exception\LogicException;
use App\Model\Entity\Server;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Author:Robert
 *
 * Class ServerLogic
 * @Bean()
 * @package App\Model\Logic
 */
class ServerLogic
{


    /**
     * Author:Robert
     *
     * @param array|string[] $columns
     * @return array
     */
    public function getCurrentServer(array $columns = ['id']): array
    {
        $server = Server::where('domain', env('SERVER_DOMAIN'))->first($columns);
        if (!$server) {
            throw new \RuntimeException('server domain is not exist');
        }
        return $server->toArray();
    }

    /**
     * 获取服务入口
     * Author:Robert
     *
     * @param int $serverId
     * @return string
     * @throws LogicException
     */
    public function getServerDomain(int $serverId): string
    {
        $server = Server::find($serverId, ['domain']);
        if (!$server) {
            throw new LogicException('服务器异常，请稍后重试');
        }
        return $server->getDomain();
    }
}
