<?php declare(strict_types=1);

namespace App\Common\Oss;

use Swlib\Saber;
use Swoft\Co;
use Swoft\Log\Helper\CLog;
use Throwable;
use Swlib\SaberGM;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Config\Annotation\Mapping\Config;

/**
 * Class Client
 * @author Robert
 * @Bean()
 * @package App\Common\Oss
 */
class Client
{

    /**
     * @Config("oss.staticUrl")
     * @var string
     */
    protected $staticUrl;

    /**
     * @Config("oss.apiUrl")
     * @var string
     */
    protected $apiUrl;

    /**
     * @Config("oss.appId")
     * @var string
     */
    protected $appId;

    /**
     * @Config("oss.appSecret")
     * @var string
     */
    protected $appSecret;

    private const UPLOAD_TIME_OUT = 60;

    /**
     * @param string $path
     * @param string $dist
     * @return bool
     * @author Robert
     */
    public function pull(string $path, string $dist): bool
    {
        try {
            $response = SaberGM::download($this->staticUrl . '/' . $path, $dist);
            if (!$response->success) {
                return false;
            }
            return true;
        } catch (Throwable $e) {
            if (file_exists($dist)) {
                Co::create(function () use ($dist) {
                    @unlink($dist);
                }, false);
            }
            return false;
        }
    }

    /**
     * @param array $files
     * @return mixed
     * @author Robert
     */
    public function delete(array $files): array
    {
        return $this->request('delete', '/oss?' . http_build_query(['files' => $files]));
    }

    /**
     * @param string $dist
     * @return string
     * @author Robert
     */
    private function getFileName(string $dist): string
    {
        $result = explode('/', $dist);
        return end($result) ?: '';
    }

    /**
     * @param array $dist
     * @param string $tag
     * @param string $pathType
     * @param bool $rename
     * @return array
     * @author Robert
     */
    public function upload(array $dist, string $tag, string $pathType, bool $rename = false): array
    {
        $files = [];
        foreach ($dist as $index => $file) {
            $files['file' . $index] = [
                'path' => $file,
                'name' => $this->getFileName($file),
            ];
        }

        $res = $this->httpWithMultipart('/oss', $files, [
            'tag' => $tag,
            'pathType' => $pathType,
            'rename' => intval($rename),
        ]);
        $res = json_decode($res, true);

        if (!$res || !is_array($res)) {
            throw new \RuntimeException('oss upload failed');
        }
        if (!isset($res['status']) || $res['status'] !== 'success') {
            throw new \RuntimeException($res['message'] ?? 'oss upload failed');
        }
        return $res['data'] ?? [];
    }

    /**
     * @param string $appId
     * @param string $appSecret
     * @return string
     * @author Robert
     */
    protected function generateKey(string $appId, string $appSecret): string
    {
        return sha1($appId . ':' . $appSecret);
    }


    /**
     * @param string $method
     * @param string $path
     * @param array $body
     * @return array|mixed
     * @author Robert
     */
    protected function request(string $method, string $path, array $body = []): array
    {
        $res = $this->http($method, $path, $body);
        $res = json_decode($res, true);
        if (!$res || !is_array($res)) {
            throw new \RuntimeException('oss delete failed');
        }
        if (!isset($res['status']) || $res['status'] !== 'success') {
            throw new \RuntimeException($res['message'] ?? 'oss delete failed');
        }
        return $res['data'] ?? [];
    }

    /**
     * Author:Robert
     *
     * @param string $method
     * @param string $path
     * @param array $body
     * @return string
     */
    protected function http(string $method, string $path, array $body = []): string
    {
        $saber = Saber::create([
            'base_uri' => $this->apiUrl,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => $this->generateKey($this->appId, $this->appSecret),
            ],
        ]);
        $response = $saber->$method($path, $body);
        if (!$response->isSuccess()) {
            throw new \RuntimeException('oss failed');
        }
        $body = (string)$response->getBody();
        if (!$body) {
            throw new \RuntimeException('oss failed');
        }
        return $body;
    }

    /**
     * @param string $path
     * @param array $files
     * @param array $fields
     * @return string
     * @author Robert
     */
    protected function httpWithMultipart(string $path, array $files, array $fields): string
    {
        $response = SaberGM::post($this->apiUrl . $path, $fields, [
                'timeout' => self::UPLOAD_TIME_OUT,
                // Content-Type的问题 #124 https://github.com/swlib/saber/issues/124
                'content_type' => null,
                'headers' => [
                    'Authorization' => $this->generateKey($this->appId, $this->appSecret),
                ],
                'files' => $files
            ]
        );
        if (!$response->isSuccess()) {
            throw new \RuntimeException('oss failed');
        }
        $body = (string)$response->getBody();
        if (!$body) {
            throw new \RuntimeException('oss failed');
        }
        return $body;
    }


}
