<?php
/**
 * Author:Robert
 *
 */

namespace App\Http\Middleware;

use Swoft\Bean\Annotation\Mapping\Bean;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AclMiddleware
 *
 * @Bean()
 */
class AclMiddleware extends \Swoft\Auth\Middleware\AclMiddleware
{

    /**
     * Author:Robert
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return parent::process($request, $handler);
    }
}
