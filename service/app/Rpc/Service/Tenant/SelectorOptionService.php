<?php declare(strict_types=1);

namespace App\Rpc\Service\Tenant;


use App\Model\Logic\Tenant\SelectorOptionLogic;
use App\Rpc\Lib\Tenant\SelectorOptionInterface;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Rpc\Server\Annotation\Mapping\Service;

/**
 * Author:Robert
 * Class OrderAccountService
 * @Service()
 * @package App\Rpc\Service
 */
class SelectorOptionService implements SelectorOptionInterface
{

    /**
     * @Inject()
     * @var SelectorOptionLogic
     */
    protected $selectorOptionLogic;

    /**
     * Author:Robert
     *
     * @param array $filter
     * @param string $type
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getOptions(array $filter, string $type, int $page = 1, int $pageSize = 100): array
    {
        return $this->selectorOptionLogic->getOptions($filter, $type, $page, $pageSize);
    }
}
