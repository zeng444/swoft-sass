<?php declare(strict_types=1);

namespace App\Model\Logic\Tenant;

use App\Exception\LogicException;
use App\Model\Entity\User;
use App\Model\Entity\UserGroup;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Author:Robert
 *
 * Class GroupLogic
 * @Bean()
 * @package App\Model\Logic\Tenant
 */
class GroupLogic
{

    /**
     * Author:Robert
     *
     * @param int|null $id
     * @param string $name
     * @return array
     * @throws LogicException
     */
    public function touch(?int $id, string $name): array
    {
        if (!$id) {
            $userGroup = UserGroup::new();
        } else {
            $userGroup = UserGroup::find($id);
            if (!$userGroup) {
                throw new LogicException('分组不存在');
            }
        }
        $userGroup->setName($name);
        $userGroup->setUsers(0);
        if (!$userGroup->save()) {
            throw new LogicException('创建分组失败');
        }
        return [
            'id' => $userGroup->getId(),
            'name' => $userGroup->getName(),
            'users' => $userGroup->getUsers(),
            'createdAt' => $userGroup->getCreatedAt(),
            'updatedAt' => $userGroup->getUpdatedAt(),
        ];
    }

    /**
     * Author:Robert
     *
     * @param int $id
     * @return array
     * @throws LogicException
     */
    public function remove(int $id): array
    {
        $userGroup = UserGroup::find($id, ['id', 'name', 'users', 'createdAt', 'updatedAt']);
        if (!$userGroup) {
            throw new LogicException('分组不存在');
        }
        if (!$userGroup->delete()) {
            throw new LogicException('删除分组失败');
        }
        return [
            'id' => $userGroup->getId(),
            'name' => $userGroup->getName(),
            'users' => $userGroup->getUsers(),
            'createdAt' => $userGroup->getCreatedAt(),
            'updatedAt' => $userGroup->getUpdatedAt(),
        ];
    }

    /**
     * Author:Robert
     *
     * @param int $groupId
     * @return bool
     */
    public function increaseUsers(int $groupId): bool
    {
        return UserGroup::where([['id', $groupId]])->increment('users', 1) > 0;
    }

    /**
     * Author:Robert
     *
     * @param int $groupId
     * @return bool
     */
    public function decreaseUsers(int $groupId): bool
    {
        return UserGroup::where([['id', $groupId]])->decrement('users', 1) > 0;
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
        $where = [];
        if (isset($filter['name']) && $filter['name']) {
            $where[] = ['name', 'LIKE', "%{$filter['name']}%"];
        }
        $model = UserGroup::where($where);
        $data = [];
        foreach ($model->forPage($page, $pageSize)->orderBy('id', 'ASC')->get($columns) as $key => $item) {
            $data[$key] = $item->toArray();
        }
        return [
            'list' => $data,
            'count' => UserGroup::where($where)->count(),
        ];
    }

    /**
     * Author:Robert
     *
     * @param int $groupId
     * @return array
     */
    public function getGroupUsers(int $groupId): array
    {
        $users = User::where([['isDeleted', 0], ['groupId', $groupId]])->get(['id'])->toArray();
        return $users ? array_column($users, 'id') : [];
    }

    /**
     * Author:Robert
     *
     * @param int $id
     * @param array|string[] $columns
     * @return object|\Swoft\Db\Eloquent\Builder|\Swoft\Db\Eloquent\Model|null
     */
    public function getGroupById(int $id, array $columns = ['*'])
    {
        $where = [
            ['id', $id],
        ];
        return UserGroup::where($where)->first($columns);
    }

    /**
     * Author:Robert
     *
     * @param int $id
     * @param bool $withDeleted
     * @return string
     */
    public function getGroupName(int $id, bool $withDeleted = true): string
    {
        $where = [
            ['id' , $id],
        ];
        if (!$withDeleted) {
            $where[] = ['isDeleted', 0];
        }
        $userGroup = UserGroup::where($where)->first();
        return $userGroup ? (string)$userGroup->getName() : '';
    }

}
