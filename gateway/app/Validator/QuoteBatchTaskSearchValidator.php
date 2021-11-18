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
 * @Validator(name="quoteBatchTaskSearchValidator")
 * @package App\Validator
 */
class QuoteBatchTaskSearchValidator
{

    /**
     * @IsString(message="车牌号格式不合法")
     * @Length(max=15,message="车牌号请控制在30个字符以内")
     * @var string
     */
    protected $license;

    /**
     * @IsString(message="统计开关格式不合法")
     * @Enum(values={"1","0",""},message="统计开关不合法")
     * @var string
     */
    protected $withAnalysis;

    /**
     * @IsString(message="车主姓名格式不合法")
     * @Length(max=30,message="车主姓名请控制在30个字符以内")
     * @var string
     */
    protected $ownerName;

    /**
     * @IsString(message="车主电话格式不合法")
     * @Length(max=11,message="车主电话请控制在11个字符以内")
     * @var string
     */
    protected $mobile;

    /**
     * @IsString(message="状态类型格式不合法")
     * @Enum(values={"FINISH","ERROR","WAITING",""},message="状态类型不合法")
     * @var string
     */
    protected $status;

}
