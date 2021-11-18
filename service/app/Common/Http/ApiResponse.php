<?php declare(strict_types=1);

namespace App\Common\Http;


use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Author:Robert
 *
 * Class ApiResponse
 * @package App\Common\Http
 * @Bean()
 */
class ApiResponse
{

    /**
     * HTTP CODE
     *
     * @var int
     */
    public $httpCode = 200;

    /**
     * Bad Request
     */
    const HTTP_BAD_REQUEST_CODE = 400;

    /**
     * Not Found
     */
    const HTTP_NOT_FOUND_CODE = 404;

    /**
     * ok
     */
    const HTTP_OK_CODE = 200;

    /**
     *  Internal Server Error
     */
    const HTTP_INTERNAL_SERVER_ERROR_CODE = 500;
    //    const HTTP_INTERNAL_SERVER_ERROR_CODE = 200;

    /**
     * Unauthorized
     */
    const HTTP_UNAUTHORIZED_CODE = 401;

    /**
     * No Content
     */
    const HTTP_NO_CONTENT_CODE = 204;

    /**
     * @var string
     */
    private $responseType = '';

    /**
     * Author:Robert Tsang
     *
     * @var
     */
    protected $_di;

    /**
     * Author:Robert Tsang
     *
     * @var bool
     */
    protected $debug = false;


    /**
     * ApiResponse constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (isset($options['debug'])) {
            $this->debug = $options['debug'];
        }
    }

    /**
     * 设置http code
     *
     * @param $code
     */
    public function setHttpCode($code)
    {
        $this->httpCode = $code;
    }


    /**
     * Author:Robert
     *
     * @return int
     */
    public function getHttpCode():int
    {
        return $this->httpCode;
    }

    public function getResponseType(): string
    {
        return $this->responseType;
    }

    /**
     * 输出数据
     * @param $data
     * @param int $code
     * @param string $message
     * @return array
     */
    public function success($data,int $code = self::HTTP_OK_CODE, string $message = ''): array
    {
        $this->responseType = 'success';
        $this->setHttpCode($code);
        return [
            "data" => $data,
            "message" => $message,
            "status" => "success",
            "code" => (string)$code,
        ];
    }


    /**
     * 错误输出
     * @param string $message
     * @param int $code
     * @param string $exception
     * @return array
     */
    public function error(string $message, int $code = self::HTTP_INTERNAL_SERVER_ERROR_CODE,string $exception = ""): array
    {
        $this->responseType = 'error';
        if ($code < 10000) {
            $this->setHttpCode($code);
        } else {
            $this->setHttpCode(self::HTTP_OK_CODE);
        }
        return [
            "data" => $exception,
            "message" => $message,
            "status" => "error",
            "code" => (string)$code,
        ];
    }
}
