<?php declare(strict_types=1);

namespace App\Model\Constant;

/**
 * Author:Robert
 *
 * Class OrderAccount
 * @package App\Model\Constant
 */
class OrderAccount
{
    const ANALYSIS_TYPE = 'ANALYSIS';
    const QUOTATION_TYPE = 'QUOTE';
    const ALL_TYPE = 'ALL';

    const TYPE_MAP = [
        self::ANALYSIS_TYPE => '数据分析工号',
        self::QUOTATION_TYPE => '询价工号',
        self::ALL_TYPE => '询价/解析工号',
    ];
}

