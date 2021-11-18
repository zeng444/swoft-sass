<?php declare(strict_types=1);


namespace App\Http\Middleware;


use App\Http\Auth\AuthManagerService;
use App\Rpc\Client\Contract\Balancer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Request;
use Swoft\Log\Helper\CLog;

/**
 * Class AfterAuthMiddleware
 * @author Robert
 * @Bean()
 * @package App\Http\Middleware
 */
class AfterAuthMiddleware
{

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface|Request $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws \Swoft\Exception\SwoftException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Balancer $balancer */
        $balancer = \Swoft::getBean(Balancer::class);
        /** @var AuthManagerService $authManager */
        $authManager = \Swoft::getBean(AuthManagerService::class);
        if($authManager->isLoggedIn()){
            $extData = $authManager->getSession()->getExtendedData();
            if (!$extData || !isset($extData['serviceCode'])) {
                throw new \RuntimeException('service code is not exist');
            }
            $balancer->setHostName((string)$extData['serviceCode']);
        }
        return $handler->handle($request);
    }

}
