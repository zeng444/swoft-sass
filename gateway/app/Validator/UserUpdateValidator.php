<?php declare(strict_types=1);


namespace App\Validator;

use App\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\IsArray;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Mobile;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Author:Robert
 *
 * Class UserUpdateValidator
 * @Validator(name="userUpdateValidator")
 * @package App\Validator
 */
class UserUpdateValidator
{

    /**
     * @IsString(message="账号格式不合法")
     * @Length(max=30,message="账号请控制再1-30个字符以内")
     * @var string
     */
    protected $account;

    /**
     * @IsString(message="密码格式不合法")
     * @Length(max=30,message="密码请控制再1-30个字符以内")
     * @var string
     */
    protected $password;

    /**
     * @IsString(message="姓名格式不合法")
     * @Length(max=30,message="姓名请控制再1-30个字符以内")
     * @var string
     */
    protected $nickname;

    /**
     * @IsString(message="手机号格式不合法")
     * @Mobile(message="手机号格式不合法，请填写11位数字")
     * @var string
     */
    protected $mobile;


    /**
     * @NotEmpty(message="角色格式不能为空")
     * @IsInt(message="角色格式不合法")
     * @var integer
     */
    protected $roleId;

    /**
     * @IsInt(message="分组格式不合法")
     * @var integer
     */
    protected $groupId;


    /**
     * @IsArray(message="出单工号必须为数组")
     * @var array
     */
    protected $orderAccountIds;

}
