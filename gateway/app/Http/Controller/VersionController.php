<?php declare(strict_types=1);

namespace App\Http\Controller;

use App\Rpc\Lib\Tenant\TestInterface;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Bean\Annotation\Mapping\Inject;
use App\Common\Http\ApiResponse;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;

/**
 * Class VersionController
 * @Controller("versions")
 * @author Robert
 * @package App\Http\Controller
 */
class VersionController
{
    /**
     * @Inject()
     * @var ApiResponse
     */
    protected $apiResponse;

    /**
     * Author:Robert
     *
     * @RequestMapping(route="", method={RequestMethod::GET}, name="版本信息")
     * @return array
     */
    public function version(): array
    {
        return $this->apiResponse->success([
            'version' => '1.0',
        ]);
    }


}
