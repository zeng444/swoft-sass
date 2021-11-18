<?php declare(strict_types=1);

namespace App\Http\Controller\Tenant;

use App\Common\Http\ApiResponse;
use App\Http\Auth\AuthManagerService;
use App\Model\Logic\ServerLogic;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Validator\Annotation\Mapping\Validate;
use Swoft\Validator\Annotation\Mapping\ValidateType;
use Swoft\Http\Message\Request;
use Swoft\Bean\Annotation\Mapping\Inject;
use Throwable;


/**
 * Author:Robert
 *
 * Class UserSessionsController
 * @Controller()
 * @package App\Http\Controller
 */
class UserSessionsController
{

    /**
     * @Inject()
     * @var ApiResponse
     */
    protected $apiResponse;

    /**
     * @Inject()
     * @var AuthManagerService
     */
    protected $authManager;

    /**
     * @Inject()
     * @var ServerLogic
     */
    protected $serverLogic;

    /**
     * Author:Robert
     * @RequestMapping(route="/userSessions", method={RequestMethod::POST}, name="商家登录")
     * @Validate(validator="userSessionValidator", type=ValidateType::BODY)
     * @param Request $request
     * @return array
     * @throws Throwable
     */
    public function login(Request $request): array
    {
        $tenant = $request->input('tenant', '');
        $account = $request->input('account', '');
        $password = $request->input('password', '');
        $session = $this->authManager->accountLogin($tenant, $account, $password);
        $extData = $session->getExtendedData();
        return $this->apiResponse->success(array_merge([
            //            'tenantId' => (int)$session->getIdentity(),
            'userId' => currentUserId(),
            'account' => $extData['account'],
            'nickname' => $extData['nickname'],
            'roleId' => $extData['roleId'],
            'isSuper' => $extData['isSuper'],
            'token' => sprintf('Bearer %s', $session->getToken()),
            'expiredAt' => date('Y-m-d H:i:s', $session->getExpirationTime()),
            'redirect' =>  $this->serverLogic->getServerDomain($extData['serverId']),
        ], []));
    }

}
