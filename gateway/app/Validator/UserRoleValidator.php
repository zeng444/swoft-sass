<?php declare(strict_types=1);


namespace App\Validator;

use Swoft\Validator\Annotation\Mapping\Enum;
use Swoft\Validator\Annotation\Mapping\IsArray;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Class UserRoleValidator
 * @Validator(name="userRoleValidator")
 * @author Robert
 * @package App\Validator
 */
class UserRoleValidator
{

    /**
     * @Required()
     * @IsString(message="角色名称格式不合法")
     * @Length(min=1,max=40,message="角色名称请控制再1-40个字符以内")
     * @var string
     */
    protected $name;

    /**
     * @Required()
     * @IsString("数据查看权限格式格式不合法");
     * @Enum(message="数据查看权限格式错误",values={"FULL","PERSONAL","GROUP"})
     * @var string
     */
    protected $reader;

    /**
     * @IsString("备注信息格式不合法")
     * @Length(max=40,message="备注信息请控制再40个字符以内")
     * @var string
     */
    protected $remark;

    /**
     * @Required()
     * @NotEmpty("页面权限不能为空");
     * @IsArray(message="页面权限请使用数组格式")
     * @var array
     */
    protected $menuIds;
}
