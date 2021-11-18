<?php declare(strict_types=1);

namespace App\Model\Logic;

use App\Rpc\Lib\Tenant\SelectorOptionInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;


/**
 * Author:Robert
 *
 * Class OrderAccountLogic
 * @Bean()
 * @package App\Model\Logic
 */
class SelectorOptionLogic
{

    /**
     * @Reference("biz.pool")
     * @var SelectorOptionInterface
     */
    protected $selectorOptionService;

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
        return $this->selectorOptionService->getOptions($filter, $type, $page, $pageSize);
    }

}
