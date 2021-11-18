<?php declare(strict_types=1);

namespace App\Model\Logic;

use App\Rpc\Lib\Tenant\UserGroupInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;

/**
 * Author:Robert
 *
 * Class UserGroupLogic
 * @Bean()
 * @package App\Model\Logic
 */
class UserGroupLogic
{


    /**
     * @Reference("biz.pool")
     * @var UserGroupInterface
     */
    protected $userGroupService;

    /**
     * Author:Robert
     *
     * @param int|null $id
     * @param string $name
     * @return array
     */
    public function touch(?int $id, string $name): array
    {
        return $this->userGroupService->touch($id, $name);
    }

    /**
     * Author:Robert
     *
     * @param int $id
     * @return array
     */
    public function remove(int $id): array
    {
        return $this->userGroupService->remove($id);
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
    public function list(array $filter, array $columns = ['*'], int $page = 1, int $pageSize = 20, bool $withCustomerCount = false): array
    {
        return $this->userGroupService->list($filter, $columns, $page, $pageSize, $withCustomerCount);
    }


}
