<?php declare(strict_types=1);


namespace App\Annotation\Parser;


use ReflectionException;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use App\Annotation\Mapping\IsInt;
use Swoft\Validator\Exception\ValidatorException;
use Swoft\Validator\ValidatorRegister;

/**
 * Class IsIntParser
 *
 * @since 2.0
 *
 * @AnnotationParser(IsInt::class)
 */
class IsIntParser extends Parser
{
    /**
     * @param int    $type
     * @param object $annotationObject
     *
     * @return array
     * @throws ValidatorException
     * @throws ReflectionException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type != self::TYPE_PROPERTY) {
            return [];
        }
        ValidatorRegister::registerValidatorItem($this->className, $this->propertyName, $annotationObject);
        return [];
    }
}
