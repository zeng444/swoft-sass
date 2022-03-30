<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use Swoft\Db\Exception\DbException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Log\Helper\CLog;
use Swoft\Rpc\Error;
use Swoft\Rpc\Server\Exception\Handler\RpcErrorHandler;
use Swoft\Rpc\Server\Response;
use Throwable;

/**
 * Class RpcExceptionHandler
 *
 * @since 2.0
 *
 * @ExceptionHandler(\Throwable::class)
 */
class RpcExceptionHandler extends RpcErrorHandler
{
    /**
     * @param Throwable $e
     * @param Response  $response
     *
     * @return Response
     */
    public function handle(Throwable $e, Response $response): Response
    {
        // Debug is false
        if (!APP_DEBUG) {
            // just show error message
            if ($e instanceof DbException && env('APP_ENV') !== 'DEV' ) {
                $error   = Error::new($e->getCode(), '系统错误，请联系管理员', null);
            }else{
                $error = Error::new($e->getCode(), $e->getMessage(), null);
            }
        } else {
            $message = sprintf(' %s At %s line %d', $e->getMessage(), $e->getFile(), $e->getLine());
            $error = Error::new($e->getCode(), $message, null);
        }
        CLog::info('Rpc server error(%s)', $e->getMessage().$e->getTraceAsString());

        $response->setError($error);

        // Debug is true
        return $response;
    }
}
