<?php
namespace Application\Admin\Components\Db;


/**
 * Author:Robert
 *
 * Class Criteria
 * @package Application\Adminfarm\Components\Db
 */
class Criteria
{

    /**
     * @var \Phalcon\Mvc\Model\Criteria
     */
    public $criteria;

    /**
     * @var
     */
    public $modelName;

    /**
     * @var
     */
    public $postData;

    /**
     * @var
     */
    public $di;

    /**
     *
     */
    const PAGE_SIZE = 12;

    /**
     * @param \Phalcon\Mvc\Model\Criteria $criteria
     */
    public function __construct(\Phalcon\Mvc\Model\Criteria $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @param $di
     * @param $modelName
     * @param $postData
     * @return Criteria
     */
    public static function fromInput($di, $modelName, $postData)
    {
        $searchCondition = self::generateQsFiledConditions($postData);
        $criteria = \Phalcon\Mvc\Model\Criteria::fromInput($di, $modelName, array_merge($postData, $searchCondition));
        $criteria = new self($criteria);
        $criteria->modelName = $modelName;
        $criteria->postData = $postData;
        $criteria->di = $di;
        return $criteria;
    }

    /**
     * 生成复合查询条件
     * Author:Robert
     *
     * @param $query
     * @return array
     */
    public static function generateQsFiledConditions($query)
    {
        $conditions = [];
        foreach ($query as $attribute => $val) {
            if (preg_match('/^qs/', $attribute)) {
                if (!$val) {
                    continue;
                }
                $attribute = preg_replace('/^qs__/', '', $attribute);
                $group = explode('__', $attribute);
                foreach ($group as $filed) {
                    $conditions[$filed] = $val;
                }
            }
        }
        return $conditions;

    }

    /**
     * 生成时间参数等
     *
     * @return mixed
     */
    public function getParams()
    {

        $query = $this->criteria->getParams();
        $query = $query === null ? [] : $query;
        $model = new $this->modelName;
        $meta = $model->getModelsMetaData();
        $attributes = $meta->getAttributes($model);

        //处理日期区间搜索
        foreach ($attributes as $index => $attribute) {
            $vAttr = $attribute . '_range';
            if (isset($this->postData[$vAttr]) && $this->postData[$vAttr]) {
                $start = $this->postData[$vAttr][0] ?? '';
                $end = $this->postData[$vAttr][1] ?? '';
                if ($start) {
                    $start =  date('Y-m-d 00:00:00',strtotime($start));
                    if (isset($query['conditions']) && $query['conditions']) {
                        $query['conditions'] .= " AND $attribute>=:" . $vAttr . "_start:";
                    } else {
                        $query['conditions'] = "$attribute>=:" . $vAttr . "_start:";
                    }
                    $query['bind'][$vAttr . '_start'] = $start;
                }
                if ($end) {
                    $end =  date('Y-m-d 23:59:59',strtotime($end));
                    if (isset($query['conditions']) && $query['conditions']) {
                        $query['conditions'] .= " AND $attribute<=:" . $vAttr . "_end:";
                    } else {
                        $query['conditions'] = "$attribute<=:" . $vAttr . "_end:";
                    }
                    $query['bind'][$vAttr . '_end'] = $end;
                }
            }
        }
        return $query;
    }


    /**
     * 生成排序规则
     * @param bool|false $getSql
     * @return array|string
     */
    public static function getQuerySorting($getSql = false)
    {
        $order = [];
        $request = new \Phalcon\Http\Request();

        if ($sort = $request->getQuery('sort')) {
            $sort = explode('|', $sort);
            foreach ($sort as $ord) {
                if (preg_match('/^([+-])(.*)$/', $ord, $result)) {
                    $order[$result[2]] = ($result[1] === '-') ? 'desc' : 'asc';
                } else {
                    $order[$ord] = 'asc';
                }
            }
        }
        if ($getSql === true) {
            $sortsRule = '';
            foreach ($order as $filed => $sort) {
                $sortsRule .= ',' . $filed . " " . $sort;
            }
            return substr($sortsRule, 1, strlen($sortsRule) - 1);
        } else {
            return $order;
        }

    }


}

