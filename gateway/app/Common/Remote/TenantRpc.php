<?php declare(strict_types=1);

namespace App\Common\Remote;

use App\Model\Entity\Service;
use App\Model\Entity\TenantService;
use App\Model\Logic\ServerLogic;
use App\Rpc\Client\Contract\Balancer;
use App\Rpc\Client\Contract\Extender;
use Closure;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Author:Robert
 *
 * Class TenantRpc
 * @Bean()
 * @package App\Common\Remote
 */
class TenantRpc
{

    /**
     * Author:Robert
     *
     * @param Closure $callback
     * @param int|null $tenantId
     * @return array|mixed
     */
    public function handle(Closure $callback, int $tenantId = null)
    {
        if (session()) {
            throw new \RuntimeException('Session exist');
        }
        if (!is_callable($callback)) {
            throw new \RuntimeException('Closure can\'t run');
        }
        $result = [];
        /** @var Balancer $balancer */
        $balancer = \Swoft::getBean(Balancer::class);
        $originalHostName = (string)$balancer->getHostName();
        /** @var Extender $extender */
        $extender = \Swoft::getBean(Extender::class);
        $originalExtData = $extender->getExtData();
        $services = $this->getTenantService();
        foreach ($services as $service) {
            if (!$tenantId || $tenantId === $service['tenantId']) {
                $balancer->setHostName($this->getServiceCode($service['serviceId']));
                $extender->setExtData([
                    'tenantId' => $service['tenantId'],
                    'db' => $service['dbName'],
                ]);
                $result[$service['tenantId']] = $callback($service['tenantId']);
            }
        }
        $balancer->setHostName($originalHostName);
        $extender->setExtData($originalExtData);
        return ($tenantId && $result) ? $result[$tenantId] : $result;
    }

    /**
     * Author:Robert
     *
     * @return array
     */
    protected function getTenantService(): array
    {
        /** @var ServerLogic $serverLogic */
        $serverLogic = \Swoft::getBean(ServerLogic::class);
        $server = $serverLogic->getCurrentServer(['id']);
        if (!$server) {
            throw new \RuntimeException('local server is not exits');
        }
        $tenantService = TenantService::where('serverId', $server['id'])->get(['dbName', 'tenantId', 'serviceId']);
        return $tenantService ? $tenantService->toArray() : [];
    }

    /**
     * Author:Robert
     *
     * @param int $serviceId
     * @return string
     */
    protected function getServiceCode(int $serviceId): string
    {
        return Service::where('id', $serviceId)->value('code');
    }

}
