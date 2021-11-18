<?php declare(strict_types=1);

namespace App\Validator;

use Swoft\Validator\Annotation\Mapping\IsArray;
use Swoft\Validator\Annotation\Mapping\Enum;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use App\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Author:Robert
 *
 * Class OrderAccountStatus
 * @Validator(name="orderAccountStatusValidator")
 * @package App\Validator
 */
class OrderAccountStatusValidator
{

    /**
     * @Required()
     * @IsArray(message="工号Id必须为数组")
     * @NotEmpty(message="工号Id不能为空")
     * @var array
     */
    protected $orderAccountIds;

    /**
     * @Required
     * @IsInt(message="用户状态必须为整数")
     * @Enum(values={0,1},message="用户状态不合法")
     * @var integer
     */
    protected $status;


}
