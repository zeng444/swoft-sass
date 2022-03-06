<?php declare(strict_types=1);


namespace App\Http\Controller\Tenant;


use App\Common\Http\ApiResponse;
use App\Exception\LogicException;
use App\Model\Logic\UserRoleLogic;
use Swoft\Http\Message\Request;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\Middlewares;
use App\Http\Middleware\AuthMiddleware as AuthMiddleware;
use Swoft\Validator\Annotation\Mapping\Validate;
use Swoft\Validator\Annotation\Mapping\ValidateType;
use Throwable;

/**
 * Class UserRoleController
 * @Controller("userRoles")
 * @Middlewares({
 *     @Middleware(AuthMiddleware::class)
 * })
 * @author Robert
 * @package App\Http\Controller
 */
class UserRoleController
{

    /**
     * @Inject()
     * @var ApiResponse
     */
    protected $apiResponse;

    /**
     * @Inject()
     * @var UserRoleLogic
     */
    protected $userRoleLogic;


    /**
     * Author:Robert
     * @RequestMapping(route="", method={RequestMethod::GET}, name="角色列表")
     * @RequestMapping(route="/userRoleOptions", method={RequestMethod::GET}, name="角色选择器")
     * @param Request $request
     * @return array
     */
    public function list(Request $request): array
    {
        $page = (int)$request->input('page', 1);
        $pageSize = (int)$request->input('pageSize', 20);
        $name = $request->input('name', '');
        $filter = [];
        if ($name) {
            $filter['name'] = $name;
        }
        if($request->getUriPath()==='/userRoleOptions'){
            $columns = ['id', 'name', 'isSuper'];
        }else{
            $columns = ['id', 'reader', 'isSuper', 'name', 'users', 'remark', 'createdAt', 'updatedAt'];
        }

        $result = $this->userRoleLogic->list($filter, $columns, $page, $pageSize);
        return $this->apiResponse->success([
            'list' => $result['list'],
            'count' => $result['count'],
        ]);
    }

    /**
     * Author:Robert
     *
     * @RequestMapping(route="{roleId}", method={RequestMethod::GET}, name="角色详情")
     * @param int $roleId
     * @return array
     * @throws Throwable
     */
    public function info(int $roleId): array
    {
        return $this->apiResponse->success($this->userRoleLogic->info($roleId,
            ['id', 'reader', 'name', 'remark', 'createdAt', 'updatedAt'], true));
    }

    /**
     * @RequestMapping(route="", method={RequestMethod::POST}, name="创建角色")
     * @Validate(validator="userRoleValidator", type=ValidateType::BODY)
     * @param Request $request
     * @return array
     * @throws Throwable
     * @author Robert
     */
    public function create(Request $request): array
    {
//        if (!currentIsSuper()) {
//            throw new LogicException('您没有权限');
//        }
        $name = $request->input('name');
        $reader = $request->input('reader');
        $remark = $request->input('remark', '');
        $menuIds = $request->input('menuIds', []);
        $menuIds = arrayUnique($menuIds);
        return $this->apiResponse->success($this->userRoleLogic->create($name, $menuIds, $reader, $remark));
    }

    /**
     * Author:Robert
     * @RequestMapping(route="{roleId}", method={RequestMethod::PUT}, name="编辑角色")
     * @Validate(validator="userRoleValidator", type=ValidateType::BODY)
     * @param int $roleId
     * @param Request $request
     * @return array
     * @throws Throwable
     */
    public function modify(int $roleId, Request $request): array
    {
//        if (!currentIsSuper()) {
//            throw new LogicException('您没有权限');
//        }
        $name = $request->input('name', '');
        $reader = $request->input('reader');
        $remark = $request->input('remark', '');
        $menuIds = $request->input('menuIds', []);
        $menuIds = arrayUnique($menuIds);
        return $this->apiResponse->success($this->userRoleLogic->edit($roleId, $name, $menuIds, $reader, $remark));
    }

    /**
     * @RequestMapping(route="{roleId}", method={RequestMethod::DELETE}, name="删除角色")
     * @param int $roleId
     * @return array
     * @throws Throwable
     * @author Robert
     */
    public function remove(int $roleId): array
    {
//        if (!currentIsSuper()) {
//            throw new LogicException('您没有权限');
//        }
        return $this->apiResponse->success($this->userRoleLogic->remove($roleId));
    }
}
