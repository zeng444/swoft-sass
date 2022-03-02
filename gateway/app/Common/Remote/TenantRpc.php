<?php declare(strict_types=1);

namespace App\Common\Remote;

use App\Model\Entity\Service;
use App\Model\Entity\TenantService;
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
        $services = $this->getTenantService($tenantId);
        if(!$services){
            throw new \RuntimeException('tenant is not exist');
        }
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
     * @param int|null $tenantId
     * @return array
     */
    protected function getTenantService(int $tenantId = null): array
    {
        $where = [];
        if ($tenantId) {
            $where[] = ['tenantId', $tenantId];
        } else {
            $where[] = ['tenantId', '>', 0];
        }
        return TenantService::where($where)->get(['dbName', 'tenantId', 'serviceId'])->toArray();
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
