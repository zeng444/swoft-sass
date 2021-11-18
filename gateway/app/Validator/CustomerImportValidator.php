<?php declare(strict_types=1);


namespace App\Validator;

use App\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Enum;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Author:Robert
 *
 * Class CustomerImportValidator
 * @Validator(name="customerImportValidator")
 * @package App\Validator
 */
class CustomerImportValidator
{

    /**
     * @Required()
     * @NotEmpty("文件不能为空")
     * @IsString(message="文件格式不合法")
     * @var string
     */
    protected $ossFile;

    /**
     * @Required()
     * @IsInt(message="是否覆盖格式不合法")
     * @Enum(values={0,1},message="是否覆盖不合法")
     * @var integer
     */
    protected $overwrite;
}
