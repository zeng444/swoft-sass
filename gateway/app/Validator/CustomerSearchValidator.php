<?php declare(strict_types=1);


namespace App\Validator;

use Swoft\Validator\Annotation\Mapping\Enum;
use App\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\IsArray;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Validator;


/**
 * Author:Robert
 *
 * Class CustomerSearchValidator
 * @Validator(name="customerSearchValidator")
 * @package App\Validator
 */
class CustomerSearchValidator
{

    /**
     * @IsString(message="车牌号格式不合法")
     * @Length(max=15,message="车牌号请控制在30个字符以内")
     * @var string
     */
    protected $license;

    /**
     * @IsString(message="发动机号格式不合法")
     * @Length(max=30,message="发动机号请控制在30个字符以内")
     * @var string
     */
    protected $engineNo;

    /**
     * @IsString(message="车架号格式不合法")
     * @Length(max=19,message="车架号请控制在19个字符以内")
     * @var string
     */
    protected $vin;

    /**
     * @IsString(message="车主姓名格式不合法")
     * @Length(max=30,message="车主姓名请控制在30个字符以内")
     * @var string
     */
    protected $ownerName;

    /**
     * @IsString(message="车主电话格式不合法")
     * @Length(max=11,message="车主电话请控制在11个字符以内")
     * @var string
     */
    protected $mobile;

    /**
     * @IsString(message="TAG1格式不合法")
     * @Length(max=40,message="TAG1请控制在40个字符以内")
     * @var string
     */
    protected $tag1;

    /**
     * @IsString(message="TAG2格式不合法")
     * @Length(max=40,message="TAG2请控制在40个字符以内")
     * @var string
     */
    protected $tag2;

    /**
     * @IsString(message="TAG2格式不合法")
     * @Length(max=40,message="TAG2请控制在40个字符以内")
     * @var string
     */
    protected $tag3;

    /**
     * @IsString(message="状态格式不合法")
     * @Enum(values={"FAIL","PAID","PENDING","QUOTE","UNPAID","FIRST","KEY","SUCCESS",""},message="状态值不合法")
     * @var string
     */
    protected $status;


    /**
     * @IsString(message="上年公司格式不合法")
     * @Length(max=60,message="上年公司请控制在40个字符以内")
     * @var string
     */
    protected $lastBrand;

    /**
     * @IsString(message="短信状态格式不合法")
     * @Enum(values={"PENDING","SUCCESS","FAIL",""},message="短信状态值不合法")
     * @var string
     */
    protected $smsStatus;

    /**
     * @IsArray(message="初登日期格式错误")
     * @var array
     */
    protected $firstRegisterDate;

    /**
     * @IsArray(message="保险到期日期格式错误")
     * @var array
     */
    protected $lastEndAt;

    /**
     * @IsArray(message="预约日期格式错误")
     * @var array
     */
    protected $touchAt;

    /**
     * @IsInt(message="导入编号格式错误")
     * @var integer
     */
    protected $importId;

    /**
     * @IsInt(message="所属用户格式错误")
     * @var integer
     */
    protected $userId;

    /**
     * @IsInt(message="用户分组格式错误")
     * @var integer
     */
    protected $groupId;


    /**
     * @IsString(message="车辆品牌格式不合法")
     * @Length(max=50,message="车辆品牌请控制在50个字符以内")
     * @var string
     */
    protected $brandName;

    /**
     * @IsString(message="车型编码格式不合法")
     * @Length(max=40,message="车型编码请控制在40个字符以内")
     * @var string
     */
    protected $modelCode;

}
