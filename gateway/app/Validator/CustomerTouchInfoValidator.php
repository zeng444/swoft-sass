<?php declare(strict_types=1);

namespace App\Validator;

use App\Annotation\Mapping\Date;
use Swoft\Validator\Annotation\Mapping\Enum;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;


/**
 * Author:Robert
 *
 * Class CustomerTouchInfoValidator
 * @Validator(name="customerTouchInfoValidator")
 * @package App\Validator
 */
class CustomerTouchInfoValidator
{

    /**
     * @Required()
     * @IsString(message="状态格式不合法")
     * @Enum(values={"FAIL","PAID","QUOTE","UNPAID","FIRST","KEY","SUCCESS"},message="状态格式不合法")
     * @var string
     */
    protected $status;


    /**
     * @IsString(message="跟进时间格式不合法")
     * @Date(message="跟进时间格式不合法", format="Y-m-d", allowEmpty=true)
     * @var string
     */
    protected $touchAt;

    /**
     * @IsString(message="跟进记录不合法")
     * @Length(max=2000,message="跟进记录请控制在0-2000个字符以内")
     * @var string
     */
    protected $record;
}
