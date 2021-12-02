<?php declare(strict_types=1);

namespace App\Rpc\Service\Tenant;

use App\Exception\LogicException;
use App\Model\Entity\AclRoute;
use App\Model\Logic\Tenant\UserLogic;
use App\Model\Logic\Tenant\UserRoleLogic;
use App\Rpc\Lib\Tenant\UserRoleInterface;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Rpc\Server\Annotation\Mapping\Service;
use Throwable;

/**
 * Class UserRoleService
 * @Service()
 * @author Robert
 * @package App\Rpc\Service
 */
class UserRoleService implements UserRoleInterface
{

    /**
     * @Inject()
     * @var UserRoleLogic
     */
    protected $userRoleLogic;

    /**
     * Author:Robert
     *
     * @param string $name
     * @param array $menuIds
     * @param string $reader
     * @param string $remark
     * @return array
     * @throws Throwable
     */
    public function create(string $name, array $menuIds, string $reader, string $remark = ''): array
    {
        return $this->userRoleLogic->create($name, $menuIds, $reader, $remark);
    }

    /**
     * Author:Robert
     *
     * @param int $roleId
     * @param string $name
     * @param array $menuIds
     * @param string $reader
     * @param string $remark
     * @return array
     * @throws Throwable
     */
    public function edit(int $roleId, string $name, array $menuIds, string $reader, string $remark = ''): array
    {
        return $this->userRoleLogic->edit($roleId, $name, $menuIds, $reader, $remark);
    }


    /**
     * @param int $id
     * @return array
     * @throws Throwable
     * @author Robert
     */
    public function remove(int $id): array
    {
        return $this->userRoleLogic->remove($id);
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
        return $this->userRoleLogic->list($filter, $columns, $page, $pageSize);
    }

    /**
     * Author:Robert
     *
     * @param int $id
     * @param array|string[] $columns
     * @param bool $withRoutes
     * @return array
     * @throws Throwable
     */
    public function info(int $id, array $columns = ['*'], bool $withRoutes = false): array
    {
        return $this->userRoleLogic->info($id, $columns, $withRoutes);
    }


}
