<?php declare(strict_types=1);

namespace App\Model\Logic\Manager;

use App\Exception\LogicException;
use App\Model\Entity\AclRoute;
use App\Model\Constant\UserRole as UserRoleConstant;
use App\Model\Entity\User;
use App\Model\Entity\UserRole;
use App\Model\Entity\UserRoleRoute;
use App\Model\Logic\SystemSettingLogic;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DB;

/**
 * Author:Robert
 *
 * Class UserLogic
 * @Bean()
 * @package App\Model\Logic\Manager
 */
class UserLogic
{

    protected const OAA_DEFAULT_NICKNAME = '超级管理员';

    protected const OAA_ROLE_NAME = '超级管理组';

    /**
     * @param int $tenantId
     * @param string $password
     * @return int
     * @author Robert
     */
    public function resetSuperPassword(int $tenantId, string $password = ''): int
    {
        return User::where([['isSuper', 1], ['isDeleted', 0], ['tenantId', $tenantId]])->update([
            'password' => $password,
        ]);
    }

    /**
     * 注册超管，开通企业时执行
     * Author:Robert
     *
     * @param int $tenantId
     * @param string $account
     * @param string $password
     * @param string $mobile
     * @param string $remark
     * @param array $config
     * @return array
     * @throws LogicException
     */
    public function registerSuper(int $tenantId, string $account, string $password = '', string $mobile = '', string $remark = '', array $config = []): array
    {
        if (User::where([['isSuper', 1], ['isDeleted', 0], ['tenantId', $tenantId]])->useWritePdo()->first(['id'])) {
            throw new LogicException('OAA管理员已经注册');
        }
        $date = date('Y-m-d H:i:s');
        $aclRoutes = AclRoute::where('tenantId', 0)->get(['route', 'key', 'menuId'])->toArray();
        $password = $password ?: $this->generatePassword(8);
        $nickname = self::OAA_DEFAULT_NICKNAME;
        $userRole = UserRole::new();
        $userRole->setIsSuper(1);
        $userRole->setTenantId($tenantId);
        $userRole->setIsDeleted(0);
        $userRole->setReader(UserRoleConstant::FULL_READER);
        $userRole->setName(self::OAA_ROLE_NAME);
        $userRole->setUsers(1);
        $userRole->setRemark($remark);
        $user = DB::transaction(static function () use ($userRole, $config, $tenantId, $account, $nickname, $mobile, $password, $remark, $date, $aclRoutes) {

            if (!$userRole->save()) {
                throw new LogicException('创建角色失败');
            }

            if ($aclRoutes) {
                $userRoleRouteData = [];
                foreach ($aclRoutes as $route) {
                    $userRoleRouteData[] = [
                        'tenantId' => $tenantId,
                        'userRoleId' => $userRole->getId(),
                        'route' => $route['route'],
                        'key' => $route['propertyKey'],
                        'createdAt' => $date,
                        'updatedAt' => $date,
                    ];
                }
                if (!UserRoleRoute::insertOrUpdate($userRoleRouteData, true, ['createdAt'])) {
                    throw new LogicException('创建角色失败');
                }
            }
            $user = User::new([
                'isSuper' => 1,
                'tenantId' => $tenantId,
                'account' => $account,
                'isDeleted' => 0,
                'isAvailable' => 1,
                'nickname' => $nickname,
                'mobile' => $mobile,
                'roleId' => $userRole->getId(),
                'latestLoginVer' => 0,
                'password' => $password,
            ]);
            if (!$user->save()) {
                throw new LogicException('新增人员失败');
            }
            if ($config) {
                (\Swoft::getBean(SystemSettingLogic::class))->initDefault([
                    'allowedUsers' => $config['allowedUsers'] ?? 1,
                ], $tenantId);
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
            'orderAccounts' => [],
        ];
    }


    /**
     * @param int $length
     * @return string
     * @author Robert
     */
    private function generatePassword(int $length = 6): string
    {
        $dict = [
            'A',
            'C',
            'D',
            'J',
            'K',
            '2',
            '3',
            '4',
            'L',
            'M',
            'N',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'j',
            'k',
            'm',
            'n',
            'o',
            'p',
            'q',
            'r',
            's',
            't',
            '5',
            '6',
            'E',
            'F',
            'G',
            'H',
            '7',
            '9',
        ];
        $indexes = array_rand($dict, $length);
        $str = '';
        foreach ($indexes as $index) {
            $str .= $dict[$index];
        }
        return $str;
    }

}
