<?php declare(strict_types=1);

namespace App\Model\Constant;

/**
 * Class QuoteBatch
 * @author Robert
 * @package App\Model\Constant
 */
class QuoteBatch
{
    /**
     * 成功的
     */
    public const FINISH_STATUS = 'FINISH';

    /**
     * 失败的
     */
    public const ERROR_STATUS = 'ERROR';

    public const IMMEDIATE_SEND_TYPE = 'IMMEDIATE';
    public const TIMING_SEND_TYPE = 'TIMING';

    /**
     * 等待处理
     */
    public const WAITING_STATUS = 'WAITING';

    const STATUS_MAP = [
        self::WAITING_STATUS => '待询价',
        self::ERROR_STATUS => '询价失败',
        self::FINISH_STATUS => '询价成功',
    ];

    public const STANDARD_TYPE = 'STANDARD';
    public const CUSTOM_TYPE = 'CUSTOM';
    public const FILTER_TYPE = 'FILTER';

    public const TYPE_MAP = [
        self::STANDARD_TYPE => '普通报价',
        self::CUSTOM_TYPE => '自定义报价',
        self::FILTER_TYPE => '筛选型报价',
    ];

    public const QUOTING_BATCH_STATUS = 'QUOTING';
    public const QUOTED_BATCH_STATUS = 'QUOTED';
    public const SENT_BATCH_STATUS = 'SENT';
    public const CANCELED_BATCH_STATUS = 'CANCELED';
    public const QUOTE_CANCELED_BATCH_STATUS = 'QUOTE_CANCELED';

    public const BATCH_STATUS_MAP = [
        self::QUOTING_BATCH_STATUS => '询价中',
        self::QUOTED_BATCH_STATUS => '待推送',
        self::SENT_BATCH_STATUS => '已发送',
        self::CANCELED_BATCH_STATUS => '已取消',
        self::QUOTE_CANCELED_BATCH_STATUS => '取消询价',
    ];
}
