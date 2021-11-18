<?php

namespace App\Process;

use App\Common\Async\Server;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Process\Annotation\Mapping\Process;
use Swoft\Process\Contract\ProcessInterface;
use Swoole\Process\Pool;

/**
 * Class AsyncCallerServer
 *
 * @Process()
 */
class CallerServerProcess implements ProcessInterface
{


    public const BANNER_LOGO_SMALL = "Caller Start Up";

    /**
     * @Inject()
     * @var Server
     */
    protected $callerServer;

    /**
     * @param Pool $pool
     * @param int $workerId
     * @author Robert
     */
    public function run(Pool $pool, int $workerId): void
    {
        output()->info("[workerId:$workerId]" . self::BANNER_LOGO_SMALL);
        $this->callerServer->handle($pool, $workerId);
    }
}
