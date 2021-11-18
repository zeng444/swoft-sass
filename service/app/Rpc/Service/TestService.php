<?php declare(strict_types=1);

namespace App\Rpc\Service;


use App\Model\Logic\SystemSettingLogic;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Rpc\Server\Annotation\Mapping\Service;
use Throwable;

/**
 * Class AclRouteService
 * @Service()
 * @author Robert
 * @package App\Rpc\Service
 */
class TestService
{

    /**
     * @Inject()
     * @var SystemSettingLogic
     */
    protected $systemSetting;

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
        $this->systemSetting->set($key,$val,$tenantId);
    }

    public function get(string $key, string $default = '', int $tenantId = null): string
    {

    }
}
