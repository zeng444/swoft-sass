<?php declare(strict_types=1);

use App\Http\Auth\AuthManagerService;

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

/**
 * Author:Robert
 *
 * @return mixed
 */
function session()
{
    return (Swoft::getBean(AuthManagerService::class))->getSession();
}

/**
 * Author:Robert
 *
 * @param string $key
 * @return array|mixed|string
 */
function sessionExtData(string $key = '')
{
    $session = session();
    if (!$session) {
        return [];
    }
    $ext = $session->getExtendedData() ?: [];
    return $key ? ($ext[$key] ?? '') : $ext;
}

/**
 * Author:Robert
 *
 * @return int
 */
function currentUserId(): int
{
    return sessionExtData('id');
}

/**
 * @return int
 * @author Robert
 */
function currentGroupId(): int
{
    return sessionExtData('groupId');
}

/**
 * Author:Robert
 *
 * @return bool
 */
function currentIsSuper(): bool
{
    return sessionExtData('isSuper') === 1;
}

/**
 * Author:Robert
 *
 * @return string
 */
function currentReader(): string
{
    return sessionExtData('reader');
}

/**
 * Author:Robert
 *
 * @return int
 */
function currentTenantId(): int
{
    $session = session();
    if (!$session) {
        return 0;
    }
    return (int)$session->getIdentity();
}

/**
 * 树形菜单结构搜索
 * @param array $data
 * @param string $name
 * @param string $childKey
 * @param string $searchKey
 * @return array
 * @author Robert
 */
function treeFilter(array $data, string $name = '', string $childKey = 'children', string $searchKey = 'name'): array
{
    $newData = [];
    foreach ($data as $item) {
        $sub = treeFilter($item[$childKey] ?? [], $name, $childKey, $searchKey);
        if (!$name || strpos((string)$item[$searchKey], $name) !== false) {
            $newData[] = $item;
        } elseif ($sub && sizeof($sub) > 0) {
            $item[$childKey] = $sub;
            $newData[] = $item;
        }
    }
    return $newData;
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
