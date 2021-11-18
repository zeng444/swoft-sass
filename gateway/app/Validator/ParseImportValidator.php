<?php declare(strict_types=1);


namespace App\Validator;

use App\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Enum;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * @author six
 *
 * Class ParseImportValidator
 * @Validator(name="parseImportValidator")
 * @package App\Validator
 */
class ParseImportValidator
{

    /**
     * @Required()
     * @NotEmpty("文件不能为空")
     * @IsString(message="文件格式不合法")
     * @var string
     */
    protected $ossFile;
}
