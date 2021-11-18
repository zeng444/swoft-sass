<?php declare(strict_types=1);

namespace App\Common\Sms\Mandao;

use App\Common\Sms\SmsInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Tcp\Protocol;
use Swoole\Coroutine\Client as TcpClient;
use const SWOOLE_SOCK_TCP;

//'sms1' => [
//    'sign' => '【融保界】',
//    'sn' => 'SDK-CSW-010-00106',
//    'password' => 'jBIirmzM'
//],
//
//    'sms2' => [
//    'sign' => '【车生活】',
//    'sn' => 'SDK-CSW-010-00226',
//    'password' => '535770'
//],

/**
 * Class Client
 * @author Robert
 * @package App\Common\Sms\Mandao
 */
class Client implements SmsInterface
{
    /**
     * @var
     */
    protected $sn;

    /**
     * @var
     */
    protected $password;

    /**
     * @var string
     */
    protected $host = 'sdk2.entinfo.cn';

    /**
     * @var int
     */
    protected $port = 8060;

    /**
     * @var int
     */
    protected $timeout = 5;

    /**
     * Client constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (isset($options['sn'])) {
            $this->sn = $options['sn'];
        }
        if (isset($options['password'])) {
            $this->password = $options['password'];
        }
    }

    /**
     * @param string $mobile
     * @param string $message
     * @param string $sign
     * @param string|null $sendAt
     * @param string $ext
     * @return string
     * @author Robert
     */
    public function send(string $mobile, string $message, string $sign = '',string $sendAt =  null, string $ext = ''): string
    {
        return $this->socketSend([$mobile], $message . $sign, $sendAt, $ext);
    }

    /**
     * @param array $mobiles
     * @param string $message
     * @param string $sign
     * @param string|null $sendAt
     * @param string $ext
     * @return string
     * @author Robert
     */
    public function batchSend(array $mobiles, string $message, string $sign = '', string $sendAt = null, string $ext = ''): string
    {
        return $this->socketSend($mobiles, $message . $sign, $sendAt, $ext);
    }

    /**
     * @param array $mobiles
     * @param string $message
     * @param string|null $sendAt
     * @param string $ext
     * @return string
     * @author Robert
     */
    private function socketSend(array $mobiles, string $message, string $sendAt = null,string $ext = ''): string
    {
        $proto = new Protocol();
        $client = new TcpClient(SWOOLE_SOCK_TCP);
        if (!$client->connect($this->host, $this->port, $this->timeout)) {
            return '';
        }
        $params = [
            'sn' => $this->sn, ////替换成您自己的序列号
            'pwd' => strtoupper(md5($this->sn.$this->password)), //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
            'mobile' => implode(',', $mobiles),//手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
            'content' => mb_convert_encoding($message, "GBK", "UTF-8"),//短信内容
            //            'content' => urlencode($message),//短信内容
            'ext' => $ext,
            'stime' => $sendAt ??'',//定时时间 格式为2011-6-29 11:09:21
            'rrid' => '',
        ];
        $params = http_build_query($params);
        // Send message $msg . $proto->getPackageEOf()
        if (false === $client->send($proto->packBody($this->makeHeader($params)))) {
            return '';
        }
        $res = $client->recv(2.0);
        if ($res === false) {
            return '';
        }
        if ($res === '') {
            return '';
        }
        [$head, $body] = $proto->unpackData($res);
        if (!preg_match('/<string xmlns="http:\/\/tempuri.org\/">([^<]+)<\/string>/', $body, $matched)) {
            return '';
        }
        $result = explode("-", $matched[1]);
        if (count($result) > 1) {
            //           CLog::info( '发送失败返回值为:'.$matched[1].'。请查看webservice返回值对照表');
            return '';
        }
        return (string)$matched[1];
    }


    /**
     * @param $params
     * @return string
     * @author Robert
     */
    private function makeHeader($params): string
    {
        $header = "POST /webservice.asmx/mt HTTP/1.1\r\n";
        $header .= "Host:sdk2.entinfo.cn\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: ".strlen($params)."\r\n";
        $header .= "Connection: Close\r\n\r\n";
        return $header.$params."\r\n";
    }
}
