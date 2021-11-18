<?php declare(strict_types=1);

namespace App\Http\Middleware;

use App\Common\Http\ApiResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Auth\AuthConst;
use Swoft\Auth\ErrorCode;
use Swoft\Auth\Exception\AuthException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Request;
use Swoft\Log\Helper\CLog;


/**
 * Class AuthMiddleware
 *
 * @Bean()
 */
class AuthMiddleware extends \Swoft\Auth\Middleware\AuthMiddleware
{

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface|Request $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $headerKey = $request->getHeaderLine(AuthConst::HEADER_KEY);
        if (!$headerKey) {
            throw new AuthException((string)ErrorCode::AUTH_TOKEN_INVALID,ApiResponse::HTTP_UNAUTHORIZED_CODE);
        }
        return parent::process($request, $handler);
    }

}
