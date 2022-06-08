<?php declare(strict_types=1);

namespace App\Http\Controller\Tenant;

use App\Common\Http\ApiResponse;
use App\Exception\LogicException;
use App\Model\Logic\SelectorOptionLogic;
use Swoft\Http\Message\Request;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\Middlewares;
use App\Http\Middleware\AuthMiddleware as AuthMiddleware;
use Throwable;

/**
 * Author:Robert
 *
 * Class SelectorOptionsController
 * @Controller("selectorOptions")
 *  * @Middlewares({
 *     @Middleware(AuthMiddleware::class)
 * })
 * @package App\Http\Controller
 */
class SelectorOptionsController
{

    /**
     * @Inject()
     * @var ApiResponse
     */
    protected $apiResponse;

    /**
     * @Inject()
     * @var SelectorOptionLogic
     */
    protected $selectorOptionLogic;

    /**
     * Author:Robert
     *
     * @RequestMapping(route="", method={RequestMethod::GET}, name="选项器选项")
     * @param Request $request
     * @return array
     * @throws Throwable
     */
    public function list(Request $request): array
    {
        $type = $request->input('type', '');
        $page = (int)$request->input('page', 1);
        $pageSize = (int)$request->input('pageSize', 100);
        $filter = [
            'value' => $request->input('value', '')
        ];
        if (!$type) {
            throw new LogicException('类型不能为空');
        }
        return $this->apiResponse->success([
            'list' => $this->selectorOptionLogic->getOptions($filter, $type, $page, $pageSize)
        ]);
    }

}
