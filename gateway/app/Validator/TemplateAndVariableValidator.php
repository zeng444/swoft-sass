<?php declare(strict_types=1);


namespace App\Validator;

use Swoft\Validator\Annotation\Mapping\Validator;
use Swoft\Validator\Annotation\Mapping\IsArray;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;
use App\Annotation\Mapping\IsFloat;

/**
 * Author:Robert
 *
 * Class QuoteValidator
 * @Validator(name="templateAndVariableValidator")
 * @package App\Validator
 */
class TemplateAndVariableValidator
{
    /**
     * @IsArray(message="模板变量请使用数组格式")
     * @var array
     */
    protected $variable;

    /**
     * @IsFloat(message="折扣格式不合法")
     * @var float
     */
    protected $discount;

    /**
     * @IsString(message="模板格式不合法")
     * @Length(min=1,max=800,message="模板格式请控制再1-800个字符以内")
     * @var string
     */
    protected $template;
}
