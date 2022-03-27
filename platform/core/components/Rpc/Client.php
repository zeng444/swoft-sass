<?php

namespace Application\Core\Components\Rpc;

use Application\Core\Models\Server;
use Application\Core\Models\Service;
use Application\Core\Models\TenantService;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\DiInterface;

/**
 * Class Client
 * @author Robert
 * @package Application\Core\Components\Rpc
 */
class Client implements InjectionAwareInterface
{

    private $_di;

    /**
     * @var
     */
    public $host;

    /**
     * @var string
     */
    public $id = 'CLIENT';

    /**
     * @var
     */
    public $version = '1.0';

    /**
     * @var string
     */
    public $appId;

    /**
     * @var string
     */
    public $appSecret;

    /**
     * @var
     */
    public $db;

    /**
     * @var
     */
    public $tenantId = -1;

    /**
     *
     */
    const RPC_EOL = "\r\n\r\n";

    /**
     * @return DiInterface
     */
    public function getDi()
    {
        return $this->_di;
    }

    /**
     * @param DiInterface $di
     */
    public function setDI(DiInterface $di)
    {
        $this->_di = $di;
    }

    /**
     * Client constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (isset($options['appId'])) {
            $this->appId = $options['appId'];
        }
        if (isset($options['appSecret'])) {
            $this->appSecret = $options['appSecret'];
        }
        if (isset($options['version'])) {
            $this->version = $options['version'];
        }
    }

    /**
     * Author:Robert
     *
     * @param int $tenantId
     * @param string $class
     * @param string $method
     * @param array $param
     * @param string $version
     * @return mixed
     * @throws \Exception
     */
    public function tenantDispatch(int $tenantId, string $class, string $method, array $param = [], string $version = '')
    {
        $tenantService = TenantService::findFirst([
            'conditions' => 'tenantId = :tenantId:',
            'bind' => [
                'tenantId' => $tenantId,
            ],
        ]);
        if (!$tenantService) {
            throw new \Exception('不存在的租客ID:'.$tenantId);
        }
        $service = Service::findFirst($tenantService->serviceId);
        if (!$service) {
            throw new \Exception('服务不存在');
        }
        $this->tenantId = $tenantId;
        return $this->dispatch($service->host, $tenantService->dbName, $class, $method, $param, $version);
    }

    /**
     * @param string $host
     * @param string $db
     * @param string $class
     * @param string $method
     * @param array $param
     * @param string $version
     * @return mixed
     * @throws \Exception
     * @author Robert
     */
    public function dispatch(string $host, string $db, string $class, string $method, array $param = [], string $version = '')
    {
        $this->host = $host;
        $this->db = $db;
        $result = $this->request($class, $method, $param, [
            'appId' => $this->appId,
            'appSecret' => $this->appSecret,
            'tenantId' => $this->tenantId,
            'db' => $this->db,
        ], $version);
        $res = json_decode($result, true);
        if (isset($res['error'])) {
            throw new \Exception($res['error']['message'], $res['error']['code']);
        }
        return $res['result'];
    }


    /**
     * Author:Robert
     *
     * @param string $class
     * @param string $method
     * @param array $param
     * @param array $ext
     * @param string $version
     * @return string
     */
    private function request(string $class, string $method, array $param, array $ext = [], string $version = ''): string
    {
        $version = $version ?: $this->version;
        $fp = stream_socket_client($this->host, $errno, $errstr);
        if (!$fp) {
            throw new \RuntimeException("stream_socket_client fail errno={$errno} errstr={$errstr}");
        }
        $req = [
            "jsonrpc" => '2.0',
            "method" => sprintf("%s::%s::%s", $version, $class, $method),
            'params' => $param,
            'id' => $this->id,
            'ext' => $ext,
        ];
        $data = json_encode($req) . self::RPC_EOL;
        fwrite($fp, $data);
        $result = '';
        while (!feof($fp)) {
            $tmp = stream_socket_recvfrom($fp, 10240);
            if ($pos = strpos($tmp, self::RPC_EOL)) {
                $result .= substr($tmp, 0, $pos);
                break;
            } else {
                $result .= $tmp;
            }
        }
        fclose($fp);
        return $result;

    }
}
