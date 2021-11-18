<?php declare(strict_types=1);

namespace App\Console\Command;

use App\Common\Remote\ServiceRpc;
use App\Model\Logic\Ops\DatabaseLogic;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandArgument;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Console\Exception\ConsoleException;
use Swoft\Stdlib\Helper\FileHelper;
use function input;
use function output;
use Throwable;

/**
 * Author:Robert
 *
 * Class OpsCommand
 * @Command(name="ops", coroutine=true, desc="servce database script runner")
 * @package App\Console\Command
 */
class OpsCommand
{

    const SQL_FILE_EXTENSION = 'sql';

    /**
     * Run specific SQL script on remote machine
     * @CommandMapping(name="sql")
     * @CommandArgument("sqlScript", type="string",desc="SQL scripts or file path")
     * @CommandOption("service", type="string", desc="specific service name")
     * @CommandOption("database", type="string", desc="specific database name")
     * @throws Throwable
     */
    public function sql()
    {
        $sql = input()->getFirstArg();
        if (!$sql) {
            throw new ConsoleException('sql is empty');
        }
        if (strcasecmp(FileHelper::getExtension($sql, true), self::SQL_FILE_EXTENSION) === 0) {
            $sql = @file_get_contents($sql);
        }
        $serviceCode = input()->getOpt('service', '');
        $database = input()->getOpt('database', '');
        (\Swoft::getBean(ServiceRpc::class))->handle(static function (string $serviceCode, string $dbName) use ($sql) {
            $result = (\Swoft::getBean(DatabaseLogic::class))->statement($sql, []);
            foreach ($result as $item) {
                $item["cmd"] = str_replace(["\r", "\n"], ['', ''], $item["cmd"]);
                $tag = "[$serviceCode:$dbName]";
                if ($item["err"]) {
                    output()->error($tag.' '.$item["cmd"].' '.$item["err"]);
                } else {
                    output()->success($tag.' '.$item["cmd"]);
                }
            }
        }, $serviceCode, $database);
    }

}
