<?php declare(strict_types=1);

namespace App\Rpc\Client\Contract;

use App\Http\Auth\AuthManagerService;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Config\Annotation\Mapping\Config;
use Swoft\Rpc\Client\Contract\ExtenderInterface;

/**
 * Author:Robert
 * @Bean()
 */
class Extender implements ExtenderInterface
{

    /**
     * @Config("rpc.client.appId")
     * @var string
     */
    protected $appId;

    /**
     * @Config("rpc.client.appSecret")
     * @var string
     */
    protected $appSecret;

    /**
     * @var
     */
    protected $extData = [];

    /**
     * @return array
     * @throws \Swoft\Exception\SwoftException
     * @author Robert
     */
    public function getExt(): array
    {
        /** @var AuthManagerService $authManager */
        $authManager = \Swoft::getBean(AuthManagerService::class);
        $session = $authManager->getSession();
        if ($session) {
            $extData = $session->getExtendedData();
        } else {
            $extData = $this->getExtData();
        }
        $tenantId = intval($extData['tenantId'] ?? '0');
        $db = $extData['db'] ?? '';
        if (!$db) {
            throw new \RuntimeException('RPC client miss param');
        }
        return [
            'traceid' => context()->get('traceid', ''),
            'spanid' => context()->get('spanid', ''),
            'parentid' => context()->get('parentid', ''),
            'extra' => context()->get('extra', null),
            'tenantId' => $tenantId,
            'db' => $db,
            'appId' => $this->appId,
            'appSecret' => $this->appSecret,
        ];
    }

    /**
     * Author:Robert
     *
     * @param array $data
     */
    public function setExtData(array $data): void
    {
        $this->extData = $data;
    }

    /**
     * Author:Robert
     *
     * @return array
     */
    public function getExtData(): ?array
    {
        return $this->extData;
    }

}
