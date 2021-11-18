<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Rpc\Middleware;

use App\Common\Db\DbSelector;
use App\Common\Db\MySqlConnection;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Server\Contract\MiddlewareInterface;
use Swoft\Rpc\Server\Contract\RequestHandlerInterface;
use Swoft\Rpc\Server\Contract\RequestInterface;
use Swoft\Rpc\Server\Contract\ResponseInterface;
use Swoft\Rpc\Server\Exception\RpcServerException;

/**
 * Class ServiceMiddleware
 *
 * @since 2.0
 *
 * @Bean()
 */
class ServiceMiddleware implements MiddlewareInterface
{

    const AUTH_ERROR_CODE = -32001;

    const AUTH_DB_EMPTY_CODE = -32002;

    /**
     * @param RequestInterface $request
     * @param RequestHandlerInterface $requestHandler
     *
     * @return ResponseInterface
     *
     *
     *
     * @throws RpcServerException
     */
    public function process(RequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        $ext = $request->getExt();
        if (!isset($ext['appId']) || !$ext['appId']) {
            throw new RpcServerException("Authentication failed", self::AUTH_ERROR_CODE);
        }
        if (!isset($ext['appSecret']) || !$ext['appSecret']) {
            throw new RpcServerException("Authentication failed", self::AUTH_ERROR_CODE);
        }
        $password = config('rpc');
        if (!isset($password[$ext['appId']]) || $password[$ext['appId']] !== $ext['appSecret']) {
            throw new RpcServerException("Authentication failed", self::AUTH_ERROR_CODE);
        }
        $db = $ext['db'] ?? '';
        $tenantId = intval($ext['tenantId'] ?? '');
        if (!$db) {
            throw new RpcServerException("unknown DB", self::AUTH_DB_EMPTY_CODE);
        }
        \Swoft::getBean(DbSelector::class)->setDBName($db);
        if ($tenantId > 0) {
            \Swoft::getBean(MySqlConnection::class)->setTenantId((string)$tenantId);
        }
        return $requestHandler->handle($request);
    }
}
