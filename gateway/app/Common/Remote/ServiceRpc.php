<?php  declare(strict_types=1);

namespace App\Common\Remote;

use App\Model\Logic\ServerLogic;
use App\Model\Logic\ServiceLogic;
use App\Rpc\Client\Contract\Balancer;
use App\Rpc\Client\Contract\Extender;
use Swoft\Bean\Annotation\Mapping\Bean;
use Closure;

/**
 * 批量向当前服务器下得所有服务发送命令
 * Class ServiceRpc
 * @author Robert
 * @Bean()
 * @package App\Common\Remote
 */
class ServiceRpc
{
    /**
     * @param Closure $callback
     * @param int|null $tenantId
     * @return array
     * @author Robert
     */
    public function handleOnTenant(Closure $callback, int $tenantId = null): array
    {
        if ($tenantId) {
            $serviceInfo = \Swoft::getBean(ServiceLogic::class)->getTenantService($tenantId);
            if (!$serviceInfo['db'] || !$serviceInfo['serviceCode']) {
                throw new \RuntimeException('tenant id is not exist');
            }
            return $this->handle($callback, $serviceInfo['serviceCode'], $serviceInfo['db']);
        } else {
            return $this->handle($callback);
        }
    }

    /**
     * @param Closure $callback
     * @param string $serviceCode
     * @param string $dbName
     * @return array
     * @author Robert
     */
    public function handle(Closure $callback, string $serviceCode = '', string $dbName = ''): array
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
        /** @var ServiceLogic $serviceLogic */
        $serviceLogic = \Swoft::getBean(ServiceLogic::class);
        $services = $this->getAllService();
        foreach ($services as $service) {
            if (!$serviceCode || $service['code'] == $serviceCode) {
                $databases = $serviceLogic->getDatabases($service['id']);
                foreach ($databases as $database) {
                    if (!$dbName || $dbName === $database['database']) {
                        $balancer->setHostName($service['code']);
                        $extender->setExtData([
                            'tenantId' => 0,
                            'db' => $database['database']
                        ]);
                        $result[$service['code']][$database['database']] = $callback($service['code'], $database['database']);
                    }
                }
            }
        }
        $balancer->setHostName($originalHostName);
        $extender->setExtData($originalExtData);
        return ($serviceCode && $dbName && $result) ? $result[$serviceCode][$dbName] : $result;
    }

    /**
     * @return array
     * @author Robert
     */
    protected function getAllService(): array
    {
        /** @var ServerLogic $serverLogic */
        $serverLogic = \Swoft::getBean(ServerLogic::class);
        $server = $serverLogic->getCurrentServer(['id']);
        if (!$server) {
            throw new \RuntimeException('local server is not exits');
        }
        /** @var ServiceLogic $serviceLogic */
        $serviceLogic = \Swoft::getBean(ServiceLogic::class);
        return $serviceLogic->getList($server['id'], ['id', 'code', 'tag', 'host', 'id']);
    }
}
