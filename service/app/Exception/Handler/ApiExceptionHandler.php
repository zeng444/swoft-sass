<?php declare(strict_types=1);

namespace App\Exception\Handler;

use Swoft\Http\Message\Response;
use Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler;
use Throwable;

/**
 * Class ApiExceptionHandler
 */
class ApiExceptionHandler extends AbstractHttpErrorHandler
{
    /**
     * @param Throwable $except
     * @param Response $response
     *
     * @return Response
     */
    public function handle(Throwable $except, Response $response): Response
    {
        $apiResponse = \Swoft::getBean(Response::class);
        $msg = env('APP_ENV') !== 'dev' ? sprintf('%s'.PHP_EOL.'%s'.PHP_EOL, $except->getMessage(), $except->getTraceAsString()) : '系统错误，请联系管理员';
        return $response->withData($apiResponse->error($msg));
    }
}
