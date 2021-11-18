<?php declare(strict_types=1);

namespace App\Model\Constant;

/**
 * Class Customer
 * @author Robert
 * @package App\Model\Constant
 */
class Customer
{

    const FAIL_STATUS = 'FAIL';
    const PAID_STATUS = 'PAID';
    const PEND_STATUS = 'PENDING';
    const QUOTE_STATUS = 'QUOTE';
    const UNPAID_STATUS = 'UNPAID';
    const FIRST_STATUS = 'FIRST';
    const KEY_STATUS = 'KEY';
    const SUCCESS_STATUS = 'SUCCESS';


    const PENDING_SMS_STATUS = 'PENDING';
    const SUCCESS_SMS_STATUS = 'SUCCESS';
    const FAIL_SMS_STATUS = 'FAIL';

    const  GROUP_ANALYSIS_TYPE = 'GROUP';
    const  USER_ANALYSIS_TYPE = 'USER';

    const SMS_STATUS_MAP = [
        self::PENDING_SMS_STATUS => 'PENDING',
        self::SUCCESS_SMS_STATUS => 'SUCCESS',
        self::FAIL_SMS_STATUS => 'FAIL',
    ];

    const STATUS_MAP = [
        self::PEND_STATUS => '待分配',
        self::FIRST_STATUS => '待首电',
        self::QUOTE_STATUS => '可跟进',
        self::KEY_STATUS => '重点跟进',
        self::UNPAID_STATUS => '待支付',
        self::PAID_STATUS => '已支付',
        self::SUCCESS_STATUS => '成功',
        self::FAIL_STATUS => '无效',
    ];
}
