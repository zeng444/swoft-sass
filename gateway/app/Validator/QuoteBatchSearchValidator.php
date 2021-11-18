<?php declare(strict_types=1);


namespace App\Validator;

use Swoft\Validator\Annotation\Mapping\Enum;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\IsArray;
use Swoft\Validator\Annotation\Mapping\Validator;
use Swoft\Validator\Annotation\Mapping\Length;

/**
 * Author:Robert
 *
 * Class OrderBatchRemoveValidator
 * @Validator(name="quoteBatchSearchValidator")
 * @package App\Validator
 */
class QuoteBatchSearchValidator
{

    /**
     * @IsString(message="询价类型格式不合法")
     * @Enum(values={"STANDARD","CUSTOM","FILTER",""},message="询价类型不合法")
     * @var string
     */
    protected $type;


    /**
     * @IsString(message="发送类型格式不合法")
     * @Enum(values={"IMMEDIATE","TIMING",""},message="发送类型值不合法")
     * @var string
     */
    protected $sendType;

    /**
     * @IsArray(message="询价时间格式错误")
     * @var array
     */
    protected $createdAt;

    /**
     * @IsString(message="账号格式不合法")
     * @Length(max=30,message="账号请控制在1-30个字符以内")
     * @var string
     */
    protected $account;

    /**
     * @IsString(message="系统代号格式不合法")
     * @Length(max=40,message="系统代号请控制在1-40个字符以内")
     * @var string
     */
    protected $systemCode;

}
