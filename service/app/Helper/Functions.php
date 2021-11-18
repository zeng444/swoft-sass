<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
function currentTenantId(): int
{
    return intval(\context()->get('TENANT_ID'));
}

/**
 * 获取当前运行得服务代号
 * @return string
 * @author Robert
 */
function currentServiceCode(): string
{
    return config('app.serviceCode');
}

/**
 * 获取当前访问数据库
 * @return string
 * @author Robert
 */
function currentDB(): string
{
    $bean = \App\Common\Db\DbSelector::class;
    if (!Swoft::hasBean($bean)) {
        return '';
    }
    return \Swoft::getBean($bean)->getDBName();
}

/**
 * Author:Robert
 *
 * @param array $array
 * @return array
 */
function arrayUnique(array $array): array
{
    return array_values(array_unique($array));
}
