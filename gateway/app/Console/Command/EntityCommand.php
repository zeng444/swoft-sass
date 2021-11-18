<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2020/10/23
 * Time: 13:08
 */

namespace App\Console\Command;


use Swoft\Console\Input\Input;
use Swoft\Devtool\Helper\ConsoleHelper;
use Swoft\Devtool\Migration\Exception\MigrationException;
use Swoft\Devtool\Model\Logic\EntityLogic;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandArgument;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Throwable;
use function input;

/**
 * Generate entity class by database table names[by <cyan>devtool</cyan>]
 *
 * @Command(name="entity")
 * @since 2.0
 */
class EntityCommand
{
    /**
     * @Inject()
     *
     * @var EntityLogic
     */
    protected $logic;
    public const PATH = [
        'db' => '@app/Model/Entity',
    ];

    /**
     * Generate database entity
     *
     * @CommandMapping(alias="c,gen")
     * @CommandArgument(name="table", desc="database table names", type="string")
     * @CommandOption(name="table", desc="database table names", type="string")
     * @CommandOption(name="pool", desc="choose default database pool", type="string", default="db.pool")
     * @CommandOption(name="path", desc="generate entity file path", type="string", default="@app/Model/Entity/TaxProxy")
     * @CommandOption(name="y", desc="auto generate", type="string")
     * @CommandOption(name="field_prefix", desc="database field prefix ,alias is 'fp'", type="string")
     * @CommandOption(name="table_prefix", desc="like match database table prefix, alias is 'tp'", type="string")
     * @CommandOption(name="exclude", desc="expect generate database table entity, alias is 'exc'", type="string")
     * @CommandOption(name="td", desc="generate entity template path",type="string", default="@devtool/devtool/resource/template")
     * @CommandOption(name="remove_prefix", desc="remove table prefix ,alias is 'rp'",type="string")
     *
     * @return void
     */
    public function create(): void
    {
        $pool = rtrim(input()->getOpt('pool', 'db'), '.pool');
        if (!isset(self::PATH[$pool])) {
            output()->colored('pool not exist', 'error');
            return;
        }
        $table = input()->get('table', input()->getOpt('table'));
        $isConfirm = input()->getOpt('y', true);
        $fieldPrefix = input()->getOpt('field_prefix', input()->getOpt('fp'));
        $tablePrefix = input()->getOpt('table_prefix', input()->getOpt('tp'));
        $exclude = input()->getOpt('exc', input()->getOpt('exclude'));
        $removePrefix = input()->getOpt('remove_prefix', input()->getOpt('rp'));

        try {
            $this->logic->create([
                (string)$table,
                (string)$tablePrefix,
                (string)$fieldPrefix,
                (string)$exclude,
                "{$pool}.pool",
                self::PATH[$pool],
                (bool)$isConfirm,
                '@resource/template',
                (string)$removePrefix,
            ]);
        } catch (Throwable $exception) {
            output()->colored($exception->getMessage(), 'error');
        }
    }

    /**
     * Author:LazyBench
     * @CommandMapping()
     */
    public function createTableFiles(Input $input): void
    {
        try {
            $dbName = input()->get('name');
            $isConfirm = input()->getOpt('y', false);
            if (empty($dbName)) {
                throw new MigrationException("db name param can't be empty");
            }
//            DB::db()->
//            \Swoft::getBean(MigrateLogic::class)->create($name, (bool)$isConfirm);
        } catch (Throwable $e) {
            output()->error($e->getMessage());
            ConsoleHelper::highlight($e->getTraceAsString());
        }
    }
}
