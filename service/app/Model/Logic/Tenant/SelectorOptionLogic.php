<?php declare(strict_types=1);

namespace App\Model\Logic\Tenant;

use App\Model\Entity\SelectorOption;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Author:Robert
 *
 * Class SelectorOptionLogic
 * @Bean()
 * @package App\Model\Logic\Tenant
 */
class SelectorOptionLogic
{

    /**
     * 生成选择器选项
     * Author:Robert
     *
     * @param string $type
     * @param string $entityName
     * @param string $column
     * @param array $where
     * @return array
     */
    public function make(string $type, string $entityName, string $column, array $where = []): array
    {
        $existOptions = SelectorOption::where('type', $type)->get(['id', 'value'])->toArray();
        $model = $entityName::distinct();
        if ($where) {
            $model->where($where);
        }
        $options = $model->get([$column])->toArray();
        $options = $options ? array_column($options, $column) : [];
        $diff = array_filter($existOptions, static function ($item) use ($options) {
            return !in_array($item['value'], $options);
        });
        if ($diff) {
            SelectorOption::whereIn('id', array_column($diff, 'id'))->delete();
        }
        $insert = [];
        $date = date('Y-m-d H:i:s');
        foreach ($options as $option) {
            if (!$option) {
                continue;
            }
            $insert[] = [
                'type' => $type,
                'value' => $option,
                'createdAt' => $date,
                'updatedAt' => $date,
            ];
        }
        if ($insert) {
            SelectorOption::insertOrUpdate($insert, true, ['createdAt']);
        }
        return $insert;
    }

    /**
     * 获取选择器选项
     * Author:Robert
     *
     * @param array $filter
     * @param string $type
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getOptions(array $filter, string $type, int $page = 1, int $pageSize = 100): array
    {
        $where = [
            ['type', $type]
        ];
        if (isset($filter['value']) && $filter['value']) {
            $where[] = ['value', 'LIKE', "%{$filter['value']}%"];
        }
        return SelectorOption::where($where)->forPage($page, $pageSize)->get(['value'])->toArray();
    }

}
