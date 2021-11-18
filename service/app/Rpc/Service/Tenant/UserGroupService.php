<?php declare(strict_types=1);

namespace App\Rpc\Service\Tenant;

use App\Model\Logic\Tenant\GroupLogic;
use App\Rpc\Lib\Tenant\UserGroupInterface;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Rpc\Server\Annotation\Mapping\Service;
use Throwable;

/**
 * Author:Robert
 *
 * Class GroupService
 * @Service()
 * @package App\Rpc\Service
 */
class UserGroupService implements UserGroupInterface
{

    /**
     * @Inject()
     * @var GroupLogic
     */
    protected $groupLogic;

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
        return $this->groupLogic->list($filter, $columns, $page, $pageSize);
    }

    /**
     * Author:Robert
     *
     * @param int|null $id
     * @param string $name
     * @return array
     * @throws Throwable
     */
    public function touch(?int $id, string $name): array
    {
        return $this->groupLogic->touch($id, $name);
    }

    /**
     * Author:Robert
     *
     * @param int $id
     * @return array
     * @throws Throwable
     */
    public function remove(int $id): array
    {
        return $this->groupLogic->remove($id);
    }
}
