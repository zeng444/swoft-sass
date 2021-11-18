<?php declare(strict_types=1);


namespace App\Model\Logic;

use App\Model\Entity\Service;
use App\Model\Entity\ServiceDatabase;
use App\Model\Entity\TenantService;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class ServiceLogic
 * @Bean()
 * @author Robert
 * @package App\Model\Logic
 */
class ServiceLogic
{

    /**
     * Author:Robert
     *
     * @param int $serverId
     * @param array|string[] $columns
     * @return array
     */
    public function getList(int $serverId, array $columns = ['code', 'tag', 'host']): array
    {
        return Service::where([
            ['isAvailable', '=', 1],
            ['serverId', '=', $serverId],
        ])->get($columns)->toArray();
    }

    /**
     * Author:Robert
     *
     * @param int $serviceId
     * @param array $columns
     * @return array
     */
    public function getDatabases(int $serviceId,array $columns = ['database']): array
    {
        return ServiceDatabase::where('serviceId', '=', $serviceId)->get($columns)->toArray();
    }

    /**
     * @param int $tenantId
     * @return array
     * @author Robert
     */
    public function getTenantService(int $tenantId): array
    {
        $tenantService = TenantService::where([
            ["tenantId", '=', $tenantId]
        ])->first(['serverId', 'serviceId', 'dbName']);
        if (!$tenantService) {
            throw new \RuntimeException('Can\'t match any service for the tenant');
        }
        $service = Service::find($tenantService->getServiceId());
        if (!$service || $service->getIsAvailable() == 0) {
            throw new \RuntimeException(sprintf('service [%s] is not available', $tenantService->getServiceId()));
        }
        return [
            'serverId' => $service->getServerId(),
            'serviceId' => $service->getId(),
            'serviceCode' => $service->getCode(),
            'db' => $tenantService->getDbName(),
        ];
    }



}
