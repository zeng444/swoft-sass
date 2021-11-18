<?php declare(strict_types=1);

namespace App\Rpc\Service\Manager;

use App\Model\Logic\SystemSettingLogic;
use Swoft\Rpc\Server\Annotation\Mapping\Service;
use App\Rpc\Lib\Manager\SystemSettingInterface;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * Author:Robert
 *
 * Class SystemSettingService
 * @Service()
 * @package App\Rpc\Service\Manager
 */
class SystemSettingService implements SystemSettingInterface
{

    /**
     * @Inject()
     * @var SystemSettingLogic
     */
    protected $systemSettingLogic;

    /**
     * Author:Robert
     *
     * @param int $tenantId
     * @return array
     */
    public function getAll(int $tenantId): array
    {
        return $this->systemSettingLogic->getAll($tenantId);
    }

    /**
     * Author:Robert
     *
     * @param string $key
     * @param string $val
     * @param int|null $tenantId
     * @return bool
     */
    public function set(string $key, string $val, int $tenantId = null): bool
    {
        return $this->systemSettingLogic->set($key,$val,$tenantId);
    }
}
