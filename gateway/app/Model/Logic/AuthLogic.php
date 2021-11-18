<?php declare(strict_types=1);

namespace App\Model\Logic;

use App\Rpc\Client\Contract\Balancer;
use App\Rpc\Client\Contract\Extender;
use App\Rpc\Lib\Tenant\UserInterface;
use Swoft\Auth\AuthResult;
use Swoft\Auth\Contract\AccountTypeInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Exception;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;


/**
 * Author:Robert
 *
 * @Bean()
 * Class AuthLogic
 * @package App\Model\Logic
 */
class AuthLogic implements AccountTypeInterface
{


    /**
     * @Reference("biz.pool")
     * @var UserInterface
     */
    protected $userService;

    /**
     * Author:Robert
     *
     * @param array $data
     * @return AuthResult
     * @throws Exception
     */
    public function login(array $data): AuthResult
    {
        $tenant = $data['tenant'];
        $account = $data['identity'];
        $password = $data['credential'];
        // 用户验证成功则签发token
        /** @var TenantLogic $tenantLogic */
        $tenantLogic = \Swoft::getBean(TenantLogic::class);
        $tenant = $tenantLogic->authorize($tenant);
        $authResult = new AuthResult();
        /** @var ServiceLogic $serviceLogic */
        $serviceLogic = \Swoft::getBean(ServiceLogic::class);
        // authResult 主标识 对应 jwt 中的 sub 字段
        $authResult->setIdentity((string)$tenant->getId());
        // authResult 附加数据 jwt 的 payload
        $extData = array_merge([
            'tenantId' => $tenant->getId(),
        ], $serviceLogic->getTenantService((int)$tenant->getId()));

        /** @var Balancer $balancer */
        $balancer = \Swoft::getBean(Balancer::class);
        $balancer->setHostName($extData['serviceCode']);
        /** @var Extender $extender */
        $extender = \Swoft::getBean(Extender::class);
        $extender->setExtData($extData);
        $userData = $this->userService->authorize($account, $password);
        $extData = array_merge($extData, [
            'id' => $userData['id'],
            'isSuper' => $userData['isSuper'],
            'latestLoginVer' => $userData['latestLoginVer'],
            'account' => $userData['account'],
            'nickname' => $userData['nickname'],
            'roleId' => $userData['roleId'],
            'groupId' => $userData['groupId'],
        ]);
        $authResult->setExtendedData($extData);
        return $authResult;
    }

    /**
     * 默认AUTH访问验证器
     * Author:Robert
     *
     * @param string $identity
     * @return bool
     */
    public function authenticate(string $identity): bool
    {
        return true;
    }

}
