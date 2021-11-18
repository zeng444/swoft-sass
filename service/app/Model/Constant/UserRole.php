<?php declare(strict_types=1);

namespace App\Model\Constant;

/**
 * Author:Robert
 *
 * Class UserRole
 * @package App\Model\Constant
 */
class UserRole
{
    const FULL_READER = 'FULL';
    const PERSONAL_READER = 'PERSONAL';
    const GROUP_READER = 'GROUP';
    const READER_MAP = [
        self::PERSONAL_READER => '自己的数据',
        self::GROUP_READER => '小组成员数据',
        self::FULL_READER => '全局数据',
    ];
}
