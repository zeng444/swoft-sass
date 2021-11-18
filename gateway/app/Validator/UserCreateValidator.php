<?php declare(strict_types=1);


namespace App\Validator;

use App\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\IsArray;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Mobile;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Author:Robert
 *
 * Class UserCreateValidator
 * @Validator(name="userCreateValidator")
 * @package App\Validator
 */
class UserCreateValidator
{

    /**
     * @Required()
     * @IsString(message="账号格式不合法")
     * @Length(min=1,max=30,message="账号请控制在1-30个字符以内")
     * @var string
     */
    protected $account;

    /**
     * @Required()
     * @IsString(message="密码格式不合法")
     * @Length(min=1,max=30,message="密码请控制在1-40个字符以内")
     * @var string
     */
    protected $password;

    /**
     * @Required()
     * @IsString(message="姓名格式不合法")
     * @Length(min=1,max=30,message="姓名请控制在1-30个字符以内")
     * @var string
     */
    protected $nickname;

    /**
     * @Required()
     * @IsString(message="手机号格式不合法")
     * @Mobile(message="手机号格式不合法，请填写11位数字")
     * @var string
     */
    protected $mobile;


    /**
     * @Required()
     * @NotEmpty(message="角色不能为空")
     * @IsInt(message="角色格式不合法")
     * @var integer
     */
    protected $roleId;

    /**
     * @IsInt(message="分组必须为数字")
     * @var integer
     */
    protected $groupId = 0;

    /**
     * @IsArray(message="出单工号必须为数组")
     * @var array
     */
    protected $orderAccountIds;

}
