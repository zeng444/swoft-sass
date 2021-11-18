<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\DB;

/**
 * Trait EntityTrait
 * @author Robert
 * @package App\Model\Entity
 */
trait EntityTrait
{

    /**
     * 批量插入或更新
     * @param array $data
     * @param bool $duplicateUpdate
     * @param array $noUpdate
     * @return bool
     * @author Robert
     */
    public static function insertOrUpdate(array $data, bool $duplicateUpdate = false, array $noUpdate = []): bool
    {
        $instance = self::new();
        $table = $instance->getTable();
        $bind = [];
        $sql = [];
        $data = is_int(key($data)) ? $data : [$data];
        foreach ($data as $items) {
            $holder = [];
            foreach ($items as $value) {
                $holder[] = '?';
                $bind[] = $value;
            }
            $sql[] = '(' . implode($holder, ',') . ')';
        }
        $fields = array_keys($data[0]);
        $field = '`' . implode($fields, '`,`') . '`';
        $sql = "INSERT INTO $table ($field)VALUES" . implode($sql, ',');
        if ($duplicateUpdate) {
            $duplicateUpdateSql = [];
            foreach ($fields as $field) {
                if (!$noUpdate || !in_array($field, $noUpdate)) {
                    $duplicateUpdateSql[] = "`$field`=VALUES(`$field`)";
                }
            }
            $sql .= 'ON DUPLICATE KEY UPDATE ' . implode($duplicateUpdateSql, ',');
        }
        return DB::insert($sql, $bind);
    }
}
