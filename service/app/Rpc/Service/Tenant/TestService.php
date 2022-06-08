<?php declare(strict_types=1);

namespace App\Rpc\Service\Tenant;

use App\Model\Logic\Tenant\MessageLogic;
use App\Model\Logic\Tenant\OrderAccountLogic;
use App\Rpc\Lib\Tenant\TestInterface;
use Swoft\Rpc\Server\Annotation\Mapping\Service;

/**
 * Author:Robert
 * Class TestService
 * @Service()
 * @package App\Rpc\Service
 */
class TestService implements TestInterface
{

    public function test(): array
    {
//        \App\Common\Async\Client::async(OrderAccountLogic::class, 'info', [1]);
//        \App\Common\Async\Task::async(OrderAccountLogic::class, 'info', [1]);
//        \App\Common\Async\Task::co(OrderAccountLogic::class, 'info', [1]);
//        \App\Common\Async\Task::cos([
//            [OrderAccountLogic::class, 'info', [1]]
//        ]);
//        /** @var  MessageLogic $messageLogic */
//        $messageLogic  = \Swoft::getBean(MessageLogic::class);
//        $result = $messageLogic->send(Message::BATCH_TYPE,'18682653085','diqiu',1,32,'川AWY640','吴志华');
//        output()->success(json_encode($result));
        return [
            currentServiceCode(),
            currentDB()
        ];
    }

}
