<?php declare(strict_types=1);

namespace App\Model\Logic\Ops;

use App\Common\Remote\Client as RemoteClient;
use App\Common\Remote\ServiceRpc;
use App\Model\Logic\ServerLogic;
use App\Model\Logic\ServiceLogic;
use App\Rpc\Lib\Ops\DbInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;
use Throwable;

/**
 * Author:Robert
 *
 * Class DatabaseLogic
 * @Bean()
 * @package App\Model\Logic
 */
class DatabaseLogic
{

    /**
     * @Reference("biz.pool")
     * @var DbInterface
     */
    protected $dbService;


    /**
     * 向当前链接得服务发送SQL命令
     * @param string $sql
     * @param array $bind
     * @return array
     * @author Robert
     */
    public function statement(string $sql, array $bind = []): array
    {
        return $this->dbService->statement($sql, $bind);

    }

}
