<?php declare(strict_types=1);


namespace App\Validator;

use Swoft\Validator\Annotation\Mapping\IsString;
use App\Annotation\Mapping\Date;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Validator;


/**
 * Author:Robert
 *
 * Class CustomerValidator
 * @Validator(name="customerValidator")
 * @package App\Validator
 */
class CustomerValidator
{

    /**
     * @IsString(message="车牌号格式不合法")
     * @Length(max=15,message="车牌号请控制在30个字符以内")
     * @var string
     */
    protected $license;

    /**
     * @IsString(message="车架号格式不合法")
     * @Length(max=19,message="车架号请控制在19个字符以内")
     * @var string
     */
    protected $vin;

    /**
     * @IsString(message="发动机号格式不合法")
     * @Length(max=30,message="发动机号请控制在30个字符以内")
     * @var string
     */
    protected $engineNo;

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

    /**
     * @IsString(message="车辆初登日期格式格式不合法")
     * @Date(message="车辆初登日期格式错误", format="Y-m-d", allowEmpty=true)
     * @var string
     */
    protected $firstRegisterDate;

    /**
     * @IsString(message="车主姓名格式不合法")
     * @Length(max=30,message="车主姓名请控制在30个字符以内")
     * @var string
     */
    protected $ownerName;

    /**
     * @IsString(message="车主身份证格式不合法")
     * @Length(max=30,message="车主身份证请控制在30个字符以内")
     * @var string
     */
    protected $ownerCard;

    /**
     * @IsString(message="车主电话格式不合法")
     * @Length(max=11,message="车主电话请控制在11个字符以内")
     * @var string
     */
    protected $mobile;

    /**
     * @IsString(message="标签1格式不合法")
     * @Length(max=40,message="标签1请控制在1-30个字符以内")
     * @var string
     */
    protected $tag1;

    /**
     * @IsString(message="标签2格式不合法")
     * @Length(max=40,message="标签2请控制在1-30个字符以内")
     * @var string
     */
    protected $tag2;

    /**
     * @IsString(message="标签3格式不合法")
     * @Length(max=40,message="标签2请控制在1-30个字符以内")
     * @var string
     */
    protected $tag3;

}
