<?php declare(strict_types=1);

namespace App\Validator;

use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Author:Robert
 *
 * Class UserSessionValidator
 * @Validator(name="userSessionValidator")
 * @package App\Validator
 */
class UserSessionValidator
{

    /**
     * @Required()
     * @IsString(message="公司名称格式不合法")
     * @Length(min=1,max=30,message="公司名称请控制再1-30个字符以内")
     * @var string
     */
    protected $tenant;

    /**
     * @Required()
     * @IsString(message="账号格式不合法")
     * @Length(min=1,max=30,message="账号请控制再1-30个字符以内")
     * @var string
     */
    protected $account;

    /**
     * @Required()
     * @IsString(message="密码格式不合法")
     * @Length(min=1,max=30,message="密码请控制再1-40个字符以内")
     * @var string
     */
    protected $password;
}
