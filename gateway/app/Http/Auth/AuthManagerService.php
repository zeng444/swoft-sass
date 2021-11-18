<?php
/**
 * Author:Robert
 *
 */

namespace App\Http\Auth;

use App\Model\Logic\AuthLogic;
use App\Model\Logic\UserLogic;
use Swoft\Auth\AuthManager;
use Swoft\Auth\Contract\AuthManagerInterface;
use Swoft\Auth\AuthSession;
use Swoft\Auth\ErrorCode;
use Swoft\Auth\Exception\AuthException;
use Swoft\Auth\Parser\JWTTokenParser;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Exception\SwoftException;

/**
 * Author:Robert
 *
 * @Bean()
 * Class AuthManagerService
 * @package App\Http\Auth
 */
class AuthManagerService extends AuthManager implements AuthManagerInterface
{

    /**
     * @var string
     */
    protected $prefix = 'swoft.token.';

    protected $tokenParserClass = JWTTokenParser::class;


    /**
     * Author:Robert
     *
     * @param string $tenant
     * @param string $username
     * @param string $password
     * @return AuthSession
     */
    public function accountLogin(string $tenant, string $username, string $password): AuthSession
    {
        $this->setSessionDuration(config('auth.jwt.expire'));
        return $this->login(AuthLogic::class, [
            'tenant' => $tenant,
            'identity' => $username,
            'credential' => $password,
        ]);
    }

    /**
     * @param string $token
     *
     * @return bool
     * @throws SwoftException
     */
    public function authenticateToken(string $token): bool
    {
        $parentResult = parent::authenticateToken($token);
        if (!$parentResult) {
            return false;
        }
        $extData = $this->getSession()->getExtendedData();
        if (!isset($extData['latestLoginVer'])) {
            throw new AuthException('AUTH_TOKEN_INVALID', ErrorCode::AUTH_TOKEN_INVALID);
        }
        /** @var UserLogic $userLogic */
        $userLogic = \Swoft::getBean(UserLogic::class);
        $permissions = $userLogic->permissions($extData['id']);
        if ($permissions['latestLoginVer'] != $extData['latestLoginVer']) {
            throw new AuthException('AUTH_TOKEN_INVALID', ErrorCode::AUTH_TOKEN_INVALID);
        }
        //重写需要及时验证的权限
        $extData['latestLoginVer'] = $permissions['latestLoginVer'];
        $extData['roleId'] = $permissions['roleId'];
        $extData['isSuper'] = $permissions['isSuper'];
        $extData['groupId'] = $permissions['groupId'];
        $extData['reader'] = $permissions['reader'];
        $this->getSession()->setExtendedData($extData);
        return true;
    }
}
