<?php declare(strict_types=1);

namespace App\Rpc\Service\Ops;

use App\Rpc\Lib\Ops\DbInterface;
use Swoft\Db\DB;
use Swoft\Rpc\Server\Annotation\Mapping\Service;
use Throwable;

/**
 * @Service()
 * Author:Robert
 *
 */
class DbService implements DbInterface
{

    /**
     * Author:Robert
     *
     * @param string $sql
     * @param array $bind
     * @return array
     */
    public function statement(string $sql, array $bind = []): array
    {
        $sqls = explode(';', $sql);
        $stats = [];
        foreach ($sqls as $sql) {
            $sql = trim($sql);
            if ($sql) {
                $res = [
                    'cmd' => $sql,
                    'err' => '',
                ];
                try {
                    if (!DB::statement($sql, $bind)) {
                        $res['err'] = 'unknown error';
                    }
                } catch (Throwable $e) {
                    $res['err'] = $e->getMessage();
                }
                $stats[] = $res;
            }
        }
        return $stats;
    }

}
