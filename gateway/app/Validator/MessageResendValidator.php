<?php declare(strict_types=1);

namespace App\Validator;

use App\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;


/**
 * Author:Robert
 *
 * Class MessageResendValidator
 * @Validator(name="messageResendValidator")
 * @package App\Validator
 */
class MessageResendValidator
{
    /**
     * @Required()
     * @IsInt(message="信息ID不能为空")
     * @NotEmpty(message="信息ID不能为空")
     * @var integer
     */
    protected $messageId;
}
