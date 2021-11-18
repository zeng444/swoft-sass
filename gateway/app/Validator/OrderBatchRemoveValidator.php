<?php declare(strict_types=1);


namespace App\Validator;

use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\IsArray;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Author:Robert
 *
 * Class OrderBatchRemoveValidator
 * @Validator(name="orderBatchRemoveValidator")
 * @package App\Validator
 */
class OrderBatchRemoveValidator
{
    /**
     * @Required()
     * @NotEmpty(message="账号ID格式不合法")
     * @IsArray(message="账号ID请使用数组格式")
     * @var array
     */
    protected $orderAccountIds;
}
