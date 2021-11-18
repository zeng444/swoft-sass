<?php
namespace Application\Admin\Components\Web\Auth;

use Phalcon\DI\InjectionAwareInterface;
use Phalcon\DiInterface;
use Phalcon\Http\Request as HttpRequest;
use Application\Admin\Components\ErrorManager;
use Phalcon\Di;
use Application\Core\Models\Administrator;

/**
 *
 * @author Robert
 *
 * Class User
 * @package Application\App\Components\Web\Auth
 */
class User implements InjectionAwareInterface
{

    use ErrorManager;

    /**
     * @var
     */
    protected $_di;

    /**
     *
     */
    const AUTH_CODE_NOT_EXISTS = 401;

    /**
     *
     */
    const  ILLEGAL_AUTH_CODE = 401;

    /**
     * @var string
     */
    protected $id = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $accessToken = '';

    /**
     * Author:Robert
     *
     * @var string
     */
    protected $tokenKey = 'adminToken';

    /**
     *
     */
    const ACCESS_TOKEN_ENCRYPT_KEY = 'c5b1a27732d31838dd7b4b4c7eea39b0871ff720';


    /**
     * @param array $options
     */
    public function __construct($options = [])
    {
        if (isset($options['tokenKey'])) {
            $this->tokenKey = $options['tokenKey'];
        }
        $this->handle();
    }


    /**
     *
     * @author Robert
     *
     * @param $userIdentity
     * @param int $duration
     * @return bool
     */
    public function login(UserIdentity $userIdentity, $duration = 0)
    {

        $this->id = $userIdentity->getId();
        $this->name = $userIdentity->getName();
        $this->accessToken = $this->generateAccessToken();
        if ($this->beforeLogin() === false) {
            return false;
        }
        if (!$this->id || !$this->name || !$this->accessToken) {
            $this->setError($userIdentity->getErrorMessage(), self::ILLEGAL_AUTH_CODE);
            return false;
        }
        if ($this->afterLogin() === false) {
            return false;
        }
        return true;
    }


    /**
     *
     * @author Robert
     *
     */
    public function logout()
    {
        if ($this->beforeLogout() === false) {
            return false;
        }
        $di = Di::getDefault();
        $cookie = $di->get('cookies');
        $accessToken = $cookie->get($this->tokenKey);
        $accessToken->delete();
        if ($this->afterLogout() === false) {
            return false;
        }
        return true;
    }

    /**
     *
     * @author Robert
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     *
     * @author Robert
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @author Robert
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @author Robert
     *
     * @return string
     */
    public function generateAccessToken()
    {
        $crypt = new \Phalcon\Crypt();
        $text = json_encode([
            $this->id,
            $this->name
        ]);
        return base64_encode($crypt->encrypt($text, self::ACCESS_TOKEN_ENCRYPT_KEY));
    }

    /**
     *
     * @author Robert
     *
     * @param $token
     * @return bool|\StdClass
     */
    public static function decryptAccessToken($token)
    {
        $crypt = new \Phalcon\Crypt();
        $string = ($crypt->decrypt(base64_decode($token), self::ACCESS_TOKEN_ENCRYPT_KEY));
        if (!$string) {
            return false;
        }
        $string = json_decode($string, true);
        if (!$string) {
            return false;
        }
        if (!isset($string[0]) || !isset($string[1]) || !$string[0] || !$string[1]) {
            return false;
        }
        $std = new \StdClass();
        $std->id = $string[0];
        $std->name = $string[1];
        return $std;
    }

    /**
     *
     * @author Robert
     *
     * @return bool
     */
    protected function handle()
    {
        $di = Di::getDefault();
        $cookie = $di->get('cookies');
        $accessToken = $cookie->get($this->tokenKey);
        $this->accessToken = trim($accessToken->getValue());
        if (!$this->accessToken) {
            $this->setError('登录凭证不存在', self::AUTH_CODE_NOT_EXISTS);
            return false;
        }
        $loginInfo = $this->decryptAccessToken($accessToken);
        if (!$loginInfo) {
            $this->setError('无效的用户凭证', self::ILLEGAL_AUTH_CODE);
            return false;
        }
        $administrator = Administrator::getAdminByName($loginInfo->name);
        if (!$administrator) {
            $this->logout();
            $this->setError('无效的用户凭证', self::ILLEGAL_AUTH_CODE);
            return false;
        }
        $this->id = $loginInfo->id;
        $this->name = $loginInfo->name;
        return true;

    }

    /**
     * @param DiInterface $di
     */
    public function setDI(DiInterface $di)
    {
        $this->_di = $di;
    }

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->_di;
    }

    /**
     *
     * @author Robert
     *
     * @return bool
     */
    public function beforeLogout()
    {
        return true;
    }

    /**
     *
     * @author Robert
     *
     * @return bool
     */
    public function afterLogout()
    {
        return true;
    }

    /**
     *
     * @author Robert
     *
     * @return bool
     */
    public function beforeLogin()
    {
        return true;
    }

    /**
     *
     * @author Robert
     *
     * @return bool
     */
    public function afterLogin()
    {
        return true;
    }

}
