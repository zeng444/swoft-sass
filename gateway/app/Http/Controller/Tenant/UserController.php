<?php declare(strict_types=1);


namespace App\Http\Controller\Tenant;

use App\Common\Http\ApiResponse;
use App\Exception\LogicException;
use App\Model\Logic\UserLogic;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\Middlewares;
use App\Http\Middleware\AuthMiddleware as AuthMiddleware;
use Swoft\Validator\Annotation\Mapping\Validate;
use Swoft\Validator\Annotation\Mapping\ValidateType;
use Swoft\Http\Message\Request;
use Swoft\Bean\Annotation\Mapping\Inject;
use Throwable;

/**
 * Class UserController
 * @Controller("users")
 * @Middlewares({
 *     @Middleware(AuthMiddleware::class)
 * })
 */
class UserController
{
    /**
     * @Inject()
     * @var ApiResponse
     */
    protected $apiResponse;

    /**
     * @Inject()
     * @var UserLogic
     */
    protected $userLogic;


    /**
     * Author:Robert
     *
     * @RequestMapping(route="/userStatus", method={RequestMethod::PUT}, name="用户状态切换")
     * @Validate(validator="userStatusValidator", type=ValidateType::BODY)
     * @param Request $request
     * @return array
     * @throws Throwable
     */
    public function status(Request $request): array
    {
        if (!currentIsSuper()) {
            throw new LogicException('您没有权限');
        }
        $userIds = $request->input('userIds', []);
        $userIds = arrayUnique($userIds);
        $status = $request->input('status', '');
        if (!$status && in_array(currentUserId(), $userIds)) {
            throw new LogicException('您不能禁用您自己');
        }
        return $this->apiResponse->success([
            'result' => $this->userLogic->switchStatus($userIds, $status == '1'),
        ]);
    }

    /**
     * Author:Robert
     *
     * @RequestMapping(route="", method={RequestMethod::GET}, name="用户列表")
     * @RequestMapping(route="/userOptions", method={RequestMethod::GET}, name="用户选择器")
     * @RequestMapping(route="/customerAllocate/UserOptions", method={RequestMethod::GET}, name="资源分配用户选择器")
     * @RequestMapping(route="/customerDelete/userOptions", method={RequestMethod::GET}, name="资源删除用户选择器")
     * @RequestMapping(route="/customerAnalysis/userOptions", method={RequestMethod::GET}, name="资源统计用户选择器")
     * @param Request $request
     * @return array
     * @throws Throwable
     */
    public function list(Request $request): array
    {
        $filter = [
            'isAvailable' => $request->input('isAvailable', ''),
            'account' => $request->input('account', ''),
            'nickname' => $request->input('nickname', ''),
            'orderAccountId' => $request->input('orderAccountId', ''),
            'groupId' => $request->input('groupId', ''),
        ];
        $page = (int)$request->input('page', 1);
        $pageSize = (int)$request->input('pageSize', 20);
        if (in_array($request->getUriPath(), [
            '/userOptions',
            '/customerAnalysis/userOptions',
            '/customerAllocate/UserOptions',
            '/customerDelete/userOptions',
        ])) {
            $withCustomerCount = (int)$request->input('withCustomerCount', 0);
            $result = $this->userLogic->list($filter, ['id', 'account', 'nickname', 'mobile'], $page, $pageSize, false,$withCustomerCount==1);
        } else {
            if (!currentIsSuper()) {
                $filter['id'] = currentUserId();
            }
            $result = $this->userLogic->list($filter, ['id', 'isAvailable', 'isSuper', 'account', 'nickname', 'mobile', 'roleId', 'groupId', 'createdAt'], $page, $pageSize,true,true);
        }
        return $this->apiResponse->success($result);
    }

    /**
     * Author:Robert
     *
     * @RequestMapping(route="", method={RequestMethod::POST}, name="创建用户")
     * @Validate(validator="userCreateValidator", type=ValidateType::BODY)
     * @param Request $request
     * @return array
     * @throws Throwable
     */
    public function register(Request $request): array
    {
        if (!currentIsSuper()) {
            throw new LogicException('您没有权限');
        }
        $account = $request->input('account');
        $password = $request->input('password');
        $nickname = $request->input('nickname');
        $mobile = $request->input('mobile');
        $roleId = $request->input('roleId');
        $groupId = $request->input('groupId');
        $orderAccountIds = $request->input('orderAccountIds', []);
        $orderAccountIds = arrayUnique($orderAccountIds);
        $result = $this->userLogic->register($account, $password, $nickname, $mobile, $roleId, $groupId, $orderAccountIds);
        return $this->apiResponse->success($result);
    }


    /**
     * Author:Robert
     *
     * @RequestMapping(route="{userId}", method={RequestMethod::PUT}, name="编辑用户")
     * @Validate(validator="userUpdateValidator", type=ValidateType::BODY)
     * @param int $userId
     * @param Request $request
     * @return array
     * @throws Throwable
     */
    public function edit(int $userId, Request $request): array
    {
        if (!currentIsSuper() && currentUserId() !== $userId) {
            throw new LogicException('您没有权限');
        }
        $account = $request->input('account');
        $password = $request->input('password');
        $nickname = $request->input('nickname');
        $mobile = $request->input('mobile');
        $roleId = $request->input('roleId');
        $groupId = $request->input('groupId');
        $orderAccountIds = $request->input('orderAccountIds', []);
        $orderAccountIds = arrayUnique($orderAccountIds);
        return $this->apiResponse->success($this->userLogic->edit($userId, $account, $password, $nickname, $mobile,
            $roleId, $groupId, $orderAccountIds));
    }

    /**
     * Author:Robert
     *
     * @RequestMapping(route="{userId}", method={RequestMethod::DELETE}, name="删除用户")
     * @param int $userId
     * @return array
     * @throws Throwable
     */
    public function remove(int $userId): array
    {
        if (!currentIsSuper()) {
            throw new LogicException('您没有权限');
        }
        if ($userId === currentUserId()) {
            throw new LogicException('您不能删除自己');
        }
        return $this->apiResponse->success($this->userLogic->remove($userId));
    }


    /**
     * Author:Robert
     *
     * @RequestMapping(route="{userId}", method={RequestMethod::GET}, name="用户详情")
     * @param int $userId
     * @return array
     * @throws Throwable
     */
    public function info(int $userId): array
    {
        if (!currentIsSuper() && $userId !== currentUserId()) {
            throw new LogicException('您没有权限');
        }
        return $this->apiResponse->success($this->userLogic->info($userId, ['*'], true));
    }
}
