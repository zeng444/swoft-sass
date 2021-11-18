<?php declare(strict_types=1);


namespace App\Validator;

use Swoft\Validator\Annotation\Mapping\Enum;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\IsArray;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Author:Robert
 *
 * Class OrderAccountValidator
 * @Validator(name="orderAccountValidator")
 * @package App\Validator
 */
class OrderAccountValidator
{
    /**
     * @Required()
     * @IsString(message="系统代号格式不合法")
     * @Length(min=1,max=40,message="系统代号请控制在1-40个字符以内")
     * @var string
     */
    protected $systemCode;

    /**
     * @Required()
     * @IsString(message="账号类型格式不合法")
     * @Length(min=1,max=30,message="账号请控制在1-30个字符以内")
     * @Enum(message="账号类型不合法",values={"ANALYSIS","QUOTE","ALL"})
     * @var string
     */
    protected $type;

    /**
     * @Required()
     * @IsString(message="账号格式不合法")
     * @Length(min=1,max=30,message="账号请控制在1-30个字符以内")
     * @var string
     */
    protected $account;

    /**
     * @Required()
     * @IsString(message="账号密码格式不合法")
     * @Length(min=1,max=30,message="账号请控制在1-30个字符以内")
     * @var string
     */
    protected $password;

    /**
     * @IsString(message="描述信息格式不合法")
     * @Length(max=30,message="备注信息请控制在0-30个字符以内")
     * @var string
     */
    protected $remark;

    /**
     * @IsArray(message="账户请使用数组格式")
     * @var array
     */
    protected $userIds;

}
