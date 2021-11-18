<?php declare(strict_types=1);


namespace App\Console\Command;

use App\Common\Remote\ServiceRpc;
use App\Model\Logic\AclRouteLogic;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Throwable;

/**
 * Class MenuCommand
 *
 * @Command(name="menu",coroutine=true, desc="Build http route to ACL list")
 * @author Robert
 * @package App\Console\Command
 */
class MenuAclCommand
{

    /**
     * Generate ACL list
     * @CommandMapping(name="build")
     * @CommandOption("service", type="string", desc="specific service name")
     * @CommandOption("database", type="string", desc="specific database name")
     * @throws Throwable
     * @author Robert
     */
    public function build()
    {
        $serviceCode = input()->getOpt('service', '');
        $database = input()->getOpt('database', '');
        (\Swoft::getBean(ServiceRpc::class))->handle(static function (string $serviceCode, string $dbName) {
            /** @var AclRouteLogic $aclRouteLogic */
            $aclRouteLogic = \Swoft::getBean(AclRouteLogic::class);
            $aclRouteLogic->build();
            output()->success(sprintf('%s:%s build success', $serviceCode, $dbName));
        }, $serviceCode, $database);
    }

}
