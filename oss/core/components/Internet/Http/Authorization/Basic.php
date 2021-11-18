<?php

namespace Application\Core\Components\Internet\Http\Authorization;

use Firebase\JWT\JWT;
use Phalcon\DI\InjectionAwareInterface;
use Phalcon\DiInterface;
use Application\Core\Components\ErrorManager;
use Phalcon\Http\Request as HttpRequest;

class Basic implements InjectionAwareInterface
{

    use  ErrorManager;

    public $user;

    /**
     * Author:Robert
     *
     * @var
     */
    public $username;


    /**
     * Author:Robert
     *
     * @var
     */
    public $password;


    /**
     * Author:Robert
     *
     * @var
     */
    protected $_di;


    /**
     * @var mixed
     */
    protected $key;

    /**
     * @var mixed
     */
    protected $algs;

    /**
     * Author:Robert
     *
     * @var
     */
    public $requestHeaders;


    /**
     * Author:Robert
     *
     * @var
     */
    protected $authorization;


    const UNAUTHORIZED_HTTP_CODE = 401;





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
     */
    public function __construct(array $config)
    {
        if(isset($config['key'])){
            $this->key = $config['key'];
        }
        if(isset($config['algs'])){
            $this->algs = $config['algs'];
        }
        $request = new HttpRequest();
        $this->requestHeaders = $request->getHeaders();
        if (isset($this->requestHeaders['Authorization'])) {
            $this->authorization = $this->requestHeaders['Authorization'];
        }
    }


    /**
     * @return mixed
     * @author Robert
     */
    public function getUser(): array
    {
        return $this->user;
    }


    /**
     * Author:Robert
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (!$this->authorization) {
            $this->setError('401 Authorization Required', self::UNAUTHORIZED_HTTP_CODE);
            return false;
        }
        try {
            $authorization = ltrim($this->authorization, 'Bearer');
            $decode = JWT::decode(trim($authorization), $this->key, $this->algs);
            $this->user = (array)$decode->data;
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), self::UNAUTHORIZED_HTTP_CODE);
            return false;
        }
        return true;
    }
}
