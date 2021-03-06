<?php declare(strict_types=1);

namespace App\Common\Remote;

/**
 * Class Client
 * @author Robert
 * @package Application\Core\Components\Rpc
 */
class Client
{

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
     * Client constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (isset($options['host'])) {
            $this->host = $options['host'];
        }
        if (isset($options['appId'])) {
            $this->appId = $options['appId'];
        }
        if (isset($options['appSecret'])) {
            $this->appSecret = $options['appSecret'];
        }
        if (isset($options['version'])) {
            $this->version = $options['version'];
        }
        if (isset($options['db'])) {
            $this->db = $options['db'];
        }
        if (isset($options['tenantId'])) {
            $this->tenantId = $options['tenantId'];
        }
    }

    /**
     * Author:Robert
     *
     * @param string $class
     * @param string $method
     * @param array $param
     * @param string $version
     * @return mixed
     * @throws \Exception
     */
    public function dispatch(string $class, string $method, array $param = [], string $version = '')
    {
        $result = $this->request($class, $method, $param, [
            'appId' => $this->appId,
            'appSecret' => $this->appSecret,
            'tenantId' => $this->tenantId,
            'db' => $this->db,
        ], $version);
        $res =  json_decode($result, true);
        if(isset($res['error'])){
            throw new \Exception($res['error']['message'],$res['error']['code']);
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
    private function request(string $class,string $method,array $param,array $ext = [],string $version = ''): string
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
            $tmp = stream_socket_recvfrom($fp, 1024);
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
