<?php declare(strict_types=1);


namespace App\Validator;

use App\Annotation\Mapping\Date;
use App\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsArray;
use  App\Annotation\Mapping\IsFloat;
use Swoft\Validator\Annotation\Mapping\Enum;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;


/**
 * Author:Robert
 *
 * Class QuoteBatchValidator
 * @Validator(name="quoteBatchValidator")
 * @package App\Validator
 */
class QuoteBatchValidator
{

    /**
     * @Required()
     * @IsString(message="询价类型格式不合法")
     * @Enum(values={"STANDARD","CUSTOM","FILTER"},message="询价类型值不合法")
     * @var string
     */
    protected $type;

    /**
     * @Required()
     * @IsString(message="品牌代号格式不合法")
     * @Length(min=1,max=40,message="品牌代号格式请控制在1-40个字符以内")
     * @var string
     */
    protected $brand;

    /**
     * @IsString(message="定时发送格式不合法")
     * @Date(message="定时发送格式不合法", format="Y-m-d H:i:s", allowEmpty=true)
     * @var string
     */
    protected $sendAt;

    /**
     * @Required()
     * @IsArray(message="保险选项格式错误")
     * @NotEmpty(message="保险选项不能为空")
     * @var array
     */
    protected $coverages;


    /**
     * @Required()
     * @IsArray(message="客户信息格式错误")
     * @NotEmpty(message="客户信息不能为空")
     * @var array
     */
    protected $customerIds;

    /**
     * @Required()
     * @IsString(message="短信模版格式不合法")
     * @Length(min=1,max=3000,message="短信模版请控制在1-3000个字符以内")
     * @var string
     */
    protected $template;

    /**
     * @Required()
     * @IsInt(message="工号格式错误")
     * @NotEmpty(message="工号不能为空")
     * @var integer
     */
    protected $accountId;

    /**
     * @IsFloat(message="折扣格式不合法")
     * @var float
     */
    protected $discount;

    /**
     * @IsFloat(message="自主定价格式不合法")
     * @var float
     */
    protected $independentPriceRatio;
}
