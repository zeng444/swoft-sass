<?php declare(strict_types=1);


namespace App\Http\Controller\Tenant;

use App\Common\Http\ApiResponse;
use App\Rpc\Lib\Tenant\MenuInterface;
use Swoft\Http\Message\Request;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\Middlewares;
use App\Http\Middleware\AuthMiddleware as AuthMiddleware;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;

/**
 * Class MenuController
 * @Controller()
 * @Middlewares({
 *     @Middleware(AuthMiddleware::class)
 * })
 * @author Robert
 * @package App\Http\Controller
 */
class MenuController
{

    /**
     * @Inject()
     * @var ApiResponse
     */
    protected $apiResponse;

    /**
     * @Reference(pool="biz.pool")
     * @var MenuInterface
     */
    protected $menuService;


    /**
     * @RequestMapping(route="/menus", method={RequestMethod::GET}, name="菜单")
     * @param Request $request
     * @return array
     * @author Robert
     */
    public function tree(Request $request): array
    {
        $name = $request->input('name', '');
        return $this->apiResponse->success(treeFilter($this->menuService->tree(currentUserId()), $name));
    }

    /**
     * @RequestMapping(route="/systemMenus", method={RequestMethod::GET}, name="系统菜单")
     * @param Request $request
     * @return array
     * @author Robert
     */
    public function systemTree(Request $request): array
    {
        $name = $request->input('name', '');
        return $this->apiResponse->success(treeFilter($this->menuService->systemTree(), $name));
    }
}
