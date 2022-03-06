<?php declare(strict_types=1);

namespace App\Model\Logic\Tenant;

use App\Exception\LogicException;
use App\Model\Entity\User;
use App\Model\Entity\UserGroup;
use App\Model\Entity\UserRole;
use App\Model\Entity\UserRoleRoute;
use App\Model\Logic\SystemSettingLogic;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Db\DB;

/**
 * Author:Robert
 *
 * Class UserLogic
 * @Bean()
 * @package App\Model\Logic\Tenant
 */
class UserLogic
{

    private const PASSWORD_SALT = 'J88ks921^@!(2i30';


    /**
     * @Inject()
     * @var UserRoleLogic
     */
    protected $userRoleLogic;

    /**
     * @Inject()
     * @var GroupLogic
     */
    protected $userGroupLogic;

    /**
     * 获取当前用户可访问的路由
     * Author:Robert
     *
     * @param int $userId
     * @param string $currentRoute
     * @return bool
     * @throws LogicException
     */
    public function allowedRoutes(int $userId, string $currentRoute): bool
    {
        $roleId = User::where('id', $userId)->value('roleId');
        if (!$roleId) {
            throw new LogicException('你的角色权限错误');
        }
        $routeId = UserRoleRoute::where([['userRoleId', $roleId], ['route', $currentRoute]])->value('id');
        return !!$routeId;
    }

    /**
     * 登录验证
     * Author:Robert
     *
     * @param string $account
     * @param string $password
     * @return array
     * @throws LogicException
     */
    public function authorize(string $account, string $password): array
    {
        $model = User::where([
            ['account', $account],
            ['isDeleted', 0],
        ]);
        /** @var User $user */
        $user = $model->first([
            'id',
            'isAvailable',
            'isSuper',
            'latestLoginVer',
            'latestLoginAt',
            'account',
            'nickname',
            'mobile',
            'roleId',
            'groupId',
            'password',
        ]);
        if (!$user) {
            throw new LogicException('用户不存在');
        }
        if ($user->getIsAvailable() !== 1) {
            throw new LogicException('用户已禁用');
        }
        if (strcmp(self::encodePassword($password), $user->getPassword()) !== 0) {
            throw new LogicException('密码错误');
        }
        if (!$model->increment('latestLoginVer', 1, ['latestLoginAt' => date('Y-m-d H:i:s')])) {
            throw new LogicException('登录失败');
        }
        $user->setLatestLoginVer($user->getLatestLoginVer() + 1);
        return $user->toArray();
    }


    /**
     * Author:Robert
     *
     * @param array $filter
     * @param array|string[] $columns
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function list(array $filter, array $columns = ['*'], int $page = 1, int $pageSize = 20): array
    {
        $where = [
            ['isDeleted', 0],
        ];
        if (isset($filter['id']) && ctype_digit((string)$filter['id'])) {
            $where[] = ['id', $filter['id']];
        }
        if (isset($filter['isAvailable']) && ctype_digit((string)$filter['isAvailable'])) {
            $where[] = ['isAvailable', $filter['isAvailable']];
        }
        if (isset($filter['nickname']) && $filter['nickname']) {
            $where[] = ['nickname', 'LIKE', "%{$filter['nickname']}%"];
        }
        if (isset($filter['account']) && $filter['account']) {
            $where[] = ['account', '=', $filter['account']];
        }
        if (isset($filter['groupId']) && ctype_digit((string)$filter['groupId'])) {
            $where[] = ['groupId', '=', $filter['groupId']];
        }
        if (isset($filter['account']) && $filter['account']) {
            $where[] = ['account', '=', $filter['account']];
        }
        $model = User::where($where);
        $count = $model->count();
        $list = $model->forPage($page, $pageSize)->orderBy('id', 'ASC')->get($columns)->toArray();
        $data = [];
        foreach ($list as $index => $item) {
            $data[$index] = $item;
            if (isset($item['roleId'])) {
                if ($item['roleId']) {
                    $userRole = UserRole::find($item['roleId'], ['name']);
                    $data[$index]['roleName'] = $userRole ? $userRole->getName() : '';
                } else {
                    $data[$index]['roleName'] = '';
                }
            }
            if (isset($item['groupId'])) {
                if ($item['groupId']) {
                    $userGroup = UserGroup::find($item['groupId'], ['name']);
                    $data[$index]['groupName'] = $userGroup ? $userGroup->getName() : '';
                } else {
                    $data[$index]['groupName'] = '';
                }
            }
        }
        return [
            'list' => $data,
            'count' => $count,
        ];
    }

    /**
     * 删除用户
     * Author:Robert
     *
     * @param int $id
     * @return array
     * @throws LogicException
     */
    public function remove(int $id): array
    {
        /** @var User $user */
        $user = $this->getUserById($id, [
            'id',
            'isSuper',
            'latestLoginVer',
            'latestLoginAt',
            'account',
            'nickname',
            'mobile',
            'roleId',
            'groupId',
        ]);
        if (!$user) {
            throw new LogicException('用户不存在');
        }
        if ($user->getIsSuper() === 1) {
            throw new LogicException('您不能删除超级管理员');
        }
        $roleId = $user->getRoleId();
        $groupId = $user->getGroupId();
        $where = [
            ['id', $id],
            ['isSuper', 0],
            ['isDeleted', 0],
        ];
        $userRoleLogic = $this->userRoleLogic;
        $userGroupLogic = $this->userGroupLogic;
        DB::transaction(static function () use (
            $id,
            $roleId,
            $groupId,
            $userRoleLogic,
            $userGroupLogic,
            $where
        ) {
            $result = User::where($where)->update(['isDeleted' => $id]);
            if (!$result) {
                throw new LogicException('删除失败');
            }
            if ($roleId && !$userRoleLogic->decreaseUsers($roleId)) {
                throw new LogicException('删除失败');
            }
            if ($groupId && !$userGroupLogic->decreaseUsers($groupId)) {
                throw new LogicException('删除失败');
            }
        });
        return $user->toArray();
    }

    /**
     * 当前用户数
     * @return int
     * @author Robert
     */
    public function userCount(): int
    {
        return User::count();
    }


    /**
     * 注册
     * Author:Robert
     *
     * @param string $account
     * @param string $password
     * @param string $nickname
     * @param string $mobile
     * @param int $roleId
     * @param int|null $groupId
     * @param bool|null $isSuper
     * @return array
     * @throws LogicException
     */
    public function register(
        string $account,
        string $password,
        string $nickname,
        string $mobile,
        int $roleId,
        int $groupId = null,
        bool $isSuper = false
    ): array {
        $allowedUsers = (int)(\Swoft::getBean(SystemSettingLogic::class))->get('allowedUsers', '1');
        if($this->userCount() >= $allowedUsers){
            throw new LogicException('对不起您的坐席已满('.$allowedUsers.'席)，不能添加新的坐席');
        }
        if (User::where('account', $account)->where('isDeleted', 0)->useWritePdo()->first(['id'])) {
            throw new LogicException('账户已经存在，无法重复注册');
        }
        if (!$this->userRoleLogic->getRoleById($roleId, ['id'])) {
            throw new LogicException('账户角色不存在');
        }
        if ($groupId && !$this->userGroupLogic->getGroupById($groupId, ['id'])) {
            throw new LogicException('账户分组不存在');
        }
        $insert = [
            'isSuper' => intval($isSuper),
            'account' => $account,
            'isDeleted' => 0,
            'isAvailable' => 1,
            'nickname' => $nickname,
            'mobile' => $mobile,
            'roleId' => $roleId,
            'latestLoginVer' => 0,
            'password' => $password,
            'groupId' => $groupId,
        ];
        $user = User::new($insert);
        $userRoleLogic = $this->userRoleLogic;
        $userGroupLogic = $this->userGroupLogic;
        $user = DB::transaction(static function () use (
            $user,
            $nickname,
            $userRoleLogic,
            $userGroupLogic
        ) {
            if (!$user->save()) {
                throw new LogicException('新增人员失败');
            }
            if ($user->getRoleId() && !$userRoleLogic->increaseUsers($user->getRoleId())) {
                throw new LogicException('新增人员失败');
            }
            if ($user->getGroupId() && !$userGroupLogic->increaseUsers($user->getGroupId())) {
                throw new LogicException('新增人员失败');
            }
            if (!$user->save()) {
                throw new LogicException('更新用户坐席失败');
            }
            return $user;
        });
        return [
            'id' => $user->getId(),
            'isSuper' => $user->getIsSuper(),
            'account' => $user->getAccount(),
            'nickname' => $user->getNickname(),
            'mobile' => $user->getMobile(),
            'roleId' => $user->getId(),
            'groupId' => $user->getGroupId(),
            'latestLoginVer' => $user->getLatestLoginVer(),
            'latestLoginAt' => (string)$user->getLatestLoginAt(),
        ];
    }

    /**
     * Author:Robert
     *
     * @param int $id
     * @param string|null $account
     * @param string|null $password
     * @param string|null $nickname
     * @param string|null $mobile
     * @param int|null $roleId
     * @param int|null $groupId
     * @return array
     * @throws LogicException
     */
    public function edit(
        int $id,
        string $account = null,
        string $password = null,
        string $nickname = null,
        string $mobile = null,
        int $roleId = null,
        int $groupId = null
    ): array {
        if ($account && User::where([['account', $account], ['isDeleted', 0], ['id', '<>', $id]])->useWritePdo()
                            ->first(['id'])) {
            throw new LogicException('账户已经存在，无法重复注册');
        }
        $hasChange = $account || $password || $nickname || $mobile || $roleId || $groupId;
        /** @var User $user */
        $user = $this->getUserById($id);
        if (!$user) {
            throw new LogicException('用户不存在');
        }
        if ($hasChange) {
            if ($roleId && !$this->userRoleLogic->getRoleById($roleId, ['id'])) {
                throw new LogicException('账户角色不存在');
            }
            if ($groupId && !$this->userGroupLogic->getGroupById($groupId, ['id'])) {
                throw new LogicException('账户分组不存在');
            }
            if ($account) {
                $user->setAccount($account);
            }
            if ($password) {
                $user->setPassword($password);
            }
            if ($nickname) {
                $user->setNickname($nickname);
            }
            if ($mobile) {
                $user->setMobile($mobile);
            }
            if ($roleId) {
                $user->setRoleId($roleId);
            }
            $user->setGroupId($groupId);
        }
        return [
            'id' => $user->getId(),
            'isSuper' => $user->getIsSuper(),
            'account' => $user->getAccount(),
            'nickname' => $user->getNickname(),
            'mobile' => $user->getMobile(),
            'roleId' => $user->getId(),
            'groupId' => $user->getGroupId(),
            'latestLoginVer' => $user->getLatestLoginVer(),
            'latestLoginAt' => (string)$user->getLatestLoginAt(),
        ];
    }


    /**
     * 切换账号状态
     * Author:Robert
     *
     * @param array $userIds
     * @param bool $status
     * @return int
     */
    public function switchStatus(array $userIds, bool $status): int
    {
        return User::whereIn('id', $userIds)->where([
            ['isSuper', '0'],
            ['isDeleted', '0'],
        ])->update(['isAvailable' => intval($status)]);
    }

    /**
     * Author:Robert
     *
     * @param string $password
     * @return string
     */
    public static function encodePassword(string $password): string
    {
        return $password;
        //        return sha1($password.self::PASSWORD_SALT);
    }

    /**
     * 获取用户信息
     * Author:Robert
     *
     * @param int $id
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return object|\Swoft\Db\Eloquent\Builder|\Swoft\Db\Eloquent\Model|null
     */
    public function getUserById(int $id, array $columns = ['*'], bool $withDeleted = false)
    {
        $where = [
            ['id', $id],
        ];
        if (!$withDeleted) {
            $where[] = ['isDeleted', 0];
        }
        return User::where($where)->first($columns);
    }

    /**
     * Author:Robert
     *
     * @param int $userId
     * @param array|string[] $columns
     * @return array
     * @throws LogicException
     */
    public function info(int $userId, array $columns = ['*']): array
    {
        /** @var User $user */
        $user = $this->getUserById($userId, $columns);
        if (!$user) {
            throw new LogicException('用户不存在');
        }
        $result = $user->toArray();
        if (isset($result['roleId'])) {
            if ($result['roleId']) {
                $userRole = UserRole::find($result['roleId']);
                $result['roleName'] = $userRole ? $userRole->getName() : '';
            } else {
                $result['roleName'] = '';
            }
        }
        if (isset($result['groupId'])) {
            if ($result['groupId']) {
                $userGroup = UserGroup::find($result['groupId']);
                $result['groupName'] = $userGroup ? $userGroup->getName() : '';
            } else {
                $result['groupName'] = '';
            }
        }
        return $result;
    }

    /**
     * 用户权限表
     * Author:Robert
     *
     * @param int $userId
     * @return array
     * @throws LogicException
     */
    public function permissions(int $userId): array
    {
        $user = User::find($userId, ['isSuper', 'roleId', 'groupId', 'latestLoginVer']);
        if (!$user) {
            throw new LogicException('用户不存在');
        }
        $userRole = UserRole::find($user->getRoleId(), ['reader']);
        return [
            'latestLoginVer' => $user->getLatestLoginVer(),
            'isSuper' => $user->getIsSuper(),
            'roleId' => $user->getRoleId(),
            'groupId' => $user->getGroupId(),
            'reader' => $userRole ? $userRole->getReader() : '',
        ];
    }

    /**
     * @param int $userId
     * @return string
     * @author Robert
     */
    public function getUserNickName(int $userId): string
    {
        $user = $this->getUserById($userId);
        return $user ? $user->getNickName() : '';
    }

}
