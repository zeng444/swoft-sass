<?php declare(strict_types=1);

namespace App\Http\Controller\Tenant;

use App\Common\Http\ApiResponse;
use App\Model\Logic\UserGroupLogic;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\Middlewares;
use Swoft\Validator\Annotation\Mapping\Validate;
use Swoft\Validator\Annotation\Mapping\ValidateType;
use App\Http\Middleware\AuthMiddleware;

/**
 * Class UserGroupController
 * @Controller("userGroups")
 * @Middlewares({
 *     @Middleware(AuthMiddleware::class)
 * })
 */
class UserGroupController
{


    /**
     * @Inject()
     * @var ApiResponse
     */
    protected $apiResponse;

    /**
     * @Inject()
     * @var UserGroupLogic
     */
    protected $userGroupLogic;

    /**
     * Author:Robert
     *
     * @RequestMapping(route="", method={RequestMethod::POST}, name="创建用户分组")
     * @Validate(validator="userGroupValidator", type=ValidateType::BODY)
     * @param Request $request
     * @return array
     */
    public function create(Request $request): array
    {
        $name = $request->input('name');
        return $this->apiResponse->success([
            $this->userGroupLogic->touch(null, $name),
        ]);

    }

    /**
     * Author:Robert
     *
     * @RequestMapping(route="{groupId}", method={RequestMethod::PUT}, name="编辑用户分组")
     * @Validate(validator="userGroupValidator", type=ValidateType::BODY)
     * @param int $groupId
     * @param Request $request
     * @return array
     */
    public function edit(int $groupId, Request $request): array
    {
        $name = $request->input('name');
        return $this->apiResponse->success([
            $this->userGroupLogic->touch($groupId, $name),
        ]);
    }

    /**
     * Author:Robert
     *
     * @RequestMapping(route="", method={RequestMethod::GET}, name="用户分组列表")
     * @RequestMapping(route="/groupOptions", method={RequestMethod::GET}, name="用户分组选项")
     * @RequestMapping(route="/customerAnalysis/groupOptions", method={RequestMethod::GET}, name="资源统计分组选项")
     * @RequestMapping(route="/customerAllocate/groupOptions", method={RequestMethod::GET}, name="资源分配分组选项")
     * @param Request $request
     * @return array
     */
    public function list(Request $request): array
    {
        $name = $request->input('name', '');
        $page = (int)$request->input('page', 1);
        $pageSize = (int)$request->input('pageSize', 20);
        if (in_array($request->getUriPath(), ['/customerAnalysis/groupOptions', '/customerAllocate/groupOptions'])) {
            $withCustomerCount = (int)$request->input('withCustomerCount', 0);
            $withCustomerCount = $withCustomerCount == 1;
            $columns = ['id', 'name', 'users'];
        } else {
            $withCustomerCount = false;
            $columns = ['id', 'name', 'users', 'createdAt', 'updatedAt'];
        }
        return $this->apiResponse->success(
            $this->userGroupLogic->list(['name' => $name], $columns, $page, $pageSize, $withCustomerCount)
        );
    }

    /**
     * Author:Robert
     *
     * @RequestMapping(route="{groupId}", method={RequestMethod::DELETE}, name="用户分组删除")
     * @param int $groupId
     * @return array
     */
    public function remove(int $groupId): array
    {
        return $this->apiResponse->success([
            $this->userGroupLogic->remove($groupId),
        ]);
    }


}
