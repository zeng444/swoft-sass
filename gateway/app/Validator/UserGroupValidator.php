<?php declare(strict_types=1);

namespace App\Validator;


use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;


/**
 * Author:Robert
 *
 * Class UserGroupValidator
 * @Validator(name="userGroupValidator")
 * @package App\Validator
 */
class UserGroupValidator
{

    /**
     * @Required()
     * @IsString(message="分组名称格式不合法")
     * @Length(min=1,max=40,message="分组名称请控制再1-40个字符以内")
     * @var string
     */
    protected $name;


}
