<?php

namespace Application\Core\Components\Internet\Http\Authorization;


use Application\Core\Components\ErrorManager;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\DiInterface;
use Phalcon\Http\Request as HttpRequest;

class Oss implements InjectionAwareInterface
{
    /**
     * Author:Robert
     *
     * @var
     */
    protected $_di;

    /**
     * @var
     */
    protected $appId;

    /**
     * @var
     */
    protected $appSecret;

    /**
     * @var mixed
     */
    protected $authorization;


    const UNAUTHORIZED_HTTP_CODE = 401;

    use ErrorManager;

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
     * Oss constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (isset($config['appId'])) {
            $this->appId = $config['appId'];
        }
        if (isset($config['appSecret'])) {
            $this->appSecret = $config['appSecret'];
        }
        $request = new HttpRequest();
        $requestHeaders = $request->getHeaders();
        if (isset($requestHeaders['Authorization'])) {
            $this->authorization = $requestHeaders['Authorization'];
        }
    }

    /**
     * @return mixed
     * @author Robert
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @param string $appId
     * @param string $appSecret
     * @return string
     * @author Robert
     */
    public function generateKey(string $appId, string $appSecret): string
    {
        return sha1($this->appId . ':' . $this->appSecret);
    }

    /**
     * @return bool
     * @author Robert
     */
    public function authorize(): bool
    {
        if ($this->authorization !== $this->generateKey($this->appId, $this->appSecret)) {
            $this->setError('401 Authorization Required', self::UNAUTHORIZED_HTTP_CODE);
            return false;
        }
        return true;
    }

}