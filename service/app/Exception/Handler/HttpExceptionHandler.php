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

use App\Common\Http\ApiResponse;
use App\Exception\ApiException;
use App\Exception\LogicException;
use Swoft\Auth\Exception\AuthException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Validator\Exception\ValidatorException;
use Throwable;

/**
 * Class HttpExceptionHandler
 *
 * @ExceptionHandler(\Throwable::class)
 */
class HttpExceptionHandler extends AbstractHttpErrorHandler
{
    /**
     * @param Throwable $e
     * @param Response $response
     *
     * @return Response
     */
    public function handle(Throwable $e, Response $response): Response
    {
        /** @var ApiResponse $apiResponse */
        $apiResponse = \Swoft::getBean(ApiResponse::class);
        if ($e instanceof LogicException || $e instanceof ApiException) {
            return $this->response($response, $apiResponse->error($e->getMessage()));
        }
        if ($e instanceof AuthException) {
            return $this->response($response, $apiResponse->error($e->getMessage(), $e->getCode()));
        }
        if ($e instanceof ValidatorException) {
            return $this->response($response, $apiResponse->error($e->getMessage(), ApiResponse::HTTP_BAD_REQUEST_CODE));
        }
        Log::error('%s. (At %s line %d)', $e->getMessage(), $e->getFile(), $e->getLine());
        $msg = env('APP_ENV') === 'DEV' ? $e->getMessage().$e->getTraceAsString() : '系统错误，请联系管理员';
        return $this->response($response, $apiResponse->error($msg));
    }

    /**
     * Author:Robert
     *
     * @param Response $response
     * @param array $data
     * @param int $status
     * @return Response
     */
    final function response(Response $response, array $data, int $status = 200): Response
    {
        return $response->withStatus($status)->withHeader('Access-Control-Allow-Origin', \config('app.allowedOrigin'))
            //                        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, Agent-Id')
            //                        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            //                        ->withStatus($status)
                        ->withContentType(ContentType::JSON)->withData($data);
    }
}
