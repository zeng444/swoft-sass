<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Console\Command;

use App\Model\Constant\Message;
use App\Model\Logic\Tenant\MessageLogic;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Exception\ConsoleErrorException;
use Swoft\Console\Helper\Show;
use Swoft\Http\Server\Router\Route;
use function input;
use function output;
use function sprintf;

/**
 * Class TestCommand
 *
 * @since 2.0
 *
 * @Command(name="test",coroutine=false)
 */
class TestCommand
{
    /**
     * @CommandMapping(name="sms")
     */
    public function sms(): void
    {
        /** @var  MessageLogic $messageLogic */
       $messageLogic  = \Swoft::getBean(MessageLogic::class);
       $result = $messageLogic->send(Message::BATCH_TYPE,'18682653085','diqiu',1,32,'川AWY640','吴志华');
        output()->success($result);
    }
}
