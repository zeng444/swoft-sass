<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Common\Http\ApiResponse;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Throwable;

/**
 * Class HomeController
 * @Controller("home")
 */
class HomeController
{

    /**
     * @Inject()
     * @var ApiResponse
     */
    protected $apiResponse;


    /**
     * Author:Robert
     * @RequestMapping(route="index", method={RequestMethod::GET}, name="Hello")
     * @return array
     * @throws Throwable
     */
    public function index(): array
    {
        return $this->apiResponse->success([
            'id' => 'hello',
        ]);
    }

}
