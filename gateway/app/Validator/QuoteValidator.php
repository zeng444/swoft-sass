<?php declare(strict_types=1);


namespace App\Validator;

use App\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsFloat;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;


/**
 * Author:Robert
 *
 * Class QuoteValidator
 * @Validator(name="quoteValidator")
 * @package App\Validator
 */
class QuoteValidator
{

    /**
     * @Required()
     * @IsFloat(message="参数格式不合法")
     * @var float
     */
    protected $price;

    /**
     * @Required()
     * @IsString(message="参数格式不合法")
     * @Length(min=1,max=3000,message="参数请控制在1-3000个字符以内")
     * @var string
     */
    protected $param;

    /**
     * @Required()
     * @NotEmpty(message="工号不能为空")
     * @IsInt(message="工号格式不合法")
     * @var integer
     */
    protected $orderAccountId;

    /**
     * @Required()
     * @IsString(message="系统代号格式不合法")
     * @Length(min=1,max=30,message="系统代号请控制在1-30个字符以内")
     * @var string
     */
    protected $systemCode;
}
