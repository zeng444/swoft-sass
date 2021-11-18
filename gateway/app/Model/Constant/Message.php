<?php declare(strict_types=1);

namespace App\Model\Constant;

/**
 * Class Message
 * @author Robert
 * @package App\Model\Constant
 */
class Message
{
    const BATCH_TYPE = 'BATCH';
    const QUOTE_TYPE = 'QUOTE';


    const SUCCESS_STATUS = 'SUCCESS';
    const FAIL_STATUS = 'FAIL';

    const TYPE_MAP = [
        self::BATCH_TYPE=>'批量询价',
        self::QUOTE_TYPE=>'单一询价',
    ];

}
