<?php declare(strict_types=1);

namespace App\Model\Constant;

/**
 * Class CustomerImport
 * @author Robert
 * @package App\Model\Constant
 */
class CustomerImport
{

    const FAIL_STATUS = 'FAIL';
    const SUCCESS_STATUS = 'SUCCESS';
    const IMPORTING_STATUS = 'IMPORTING';

    const STATUS_MAP = [
        self::FAIL_STATUS => '导入失败',
        self::SUCCESS_STATUS => '导入成功',
        self::IMPORTING_STATUS => '导入中',
    ];
}
