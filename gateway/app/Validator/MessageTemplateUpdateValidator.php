<?php declare(strict_types=1);


namespace App\Validator;

use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Author:Robert
 *
 * Class MessageTemplateUpdateValidator
 * @Validator(name="messageTemplateUpdateValidator")
 * @package App\Validator
 */
class MessageTemplateUpdateValidator
{
    /**
     * @Required()
     * @IsString(message="模版信息格式不合法")
     * @Length(min=1,max=4000,message="模版信息请控制在1-4000个字符以内")
     * @var string
     */
    protected $template;
}
