<?php declare(strict_types=1);

namespace App\Validator;

use App\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;

/**
 * Author:Robert
 *
 * Class MessageResendValidator
 * @Validator(name="messageSendValidator")
 * @package App\Validator
 */
class MessageSendValidator
{

    /**
     * @IsString(message="工号格式不合法")
     * @Length(min=1,max=50,message="工号请控制再1-50个字符以内")
     * @var string
     */
    protected $accountNo;

    /**
     * @IsString(message="系统代号格式不合法")
     * @Length(min=1,max=40,message="系统代号请控制在1-40个字符以内")
     * @var string
     */
    protected $systemCode;

    /**
     * @Required()
     * @IsInt(message="客户ID不能为空")
     * @NotEmpty(message="客户ID不能为空")
     * @var integer
     */
    protected $customerId;

    /**
     * @Required()
     * @IsString(message="短信格式不合法")
     * @Length(min=1,max=490,message="短信格式请控制再1-490个字符以内")
     * @var string
     */
    protected $message;

    /**
     * @Required()
     * @IsString(message="品牌格式不合法")
     * @Length(min=1,max=40,message="品牌格式请控制再1-40个字符以内")
     * @var string
     */
    protected $brand;


}
