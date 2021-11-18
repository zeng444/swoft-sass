<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use function context;

/**
 * Class FavIconMiddleware
 *
 * @Bean()
 */
class CrossMiddleware implements MiddlewareInterface
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
        if ('OPTIONS' === $request->getMethod()) {
            $origin = $request->getHeaderLine('Origin');
            $allowedOrigin = config('app.allowedOrigin', []);
            if ($allowedOrigin && (!in_array($origin, $allowedOrigin) && $allowedOrigin!=='*')) {
                return $handler->handle($request);
            }
            $response = Context()->getResponse();
            return $this->setResponseHeaders($response);
        }
        $response = $handler->handle($request);
        return $this->setResponseHeaders($response);
    }

    /**
     * Author:Robert
     *
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function setResponseHeaders(ResponseInterface $response): ResponseInterface
    {
        return $response->withHeader('Access-Control-Allow-Origin', '*')
                        ->withHeader('Access-Control-Allow-Headers','Access-Token, Authorization, Access-Control-Request-Method, Content-Type, Accept, Origin')
                        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    }
}
