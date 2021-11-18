<?php declare(strict_types=1);

namespace App\Model\Constant;

/**
 * Author:Robert
 *
 * Class SystemBrand
 * @package App\Model\Constant
 */
class SystemBrand
{

    const PICC_CODE = 'PICC';
    const TIAN_CODE = 'TIAN';
    const CPIC_CODE = 'CPIC';
    const ZFBX_CODE = 'ZFBX';
    const PingAn_CODE = 'PingAn';
    const ALL_TRUST_CODE = 'ALL_TRUST';
    const CHINATP_CODE = 'CHINATP';
    const TAISHAN_CODE = 'TAISHAN';

    const CODE_MAP = [
        self::PICC_CODE => '人保',
        self::TIAN_CODE => '天安',
        self::CPIC_CODE => '太平洋',
        self::ZFBX_CODE => '珠峰',
        self::PingAn_CODE => '中国平安',
        self::ALL_TRUST_CODE => '永诚保险',
        self::CHINATP_CODE => '太平财险',
        self::TAISHAN_CODE => '泰山保险',
    ];

}
