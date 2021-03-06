<?php declare(strict_types=1);

namespace App\Validator\Rule;

use Swoft\Bean\Annotation\Mapping\Bean;
use App\Annotation\Mapping\IsInt;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class IsIntRule
 *
 * @since 2.0
 *
 * @Bean(IsInt::class)
 */
class IsIntRule implements RuleInterface
{
    /**
     * @param array      $data
     * @param string     $propertyName
     * @param object     $item
     * @param mixed|null $default
     * @param false $strict
     *
     * @return array
     * @throws ValidatorException
     */
    public function validate(array $data, string $propertyName, $item, $default = null, $strict = false): array
    {

        /* @var IsInt $item */
        $message = $item->getMessage();

        if (!isset($data[$propertyName]) && $default !== null) {
            $data[$propertyName] = (int)$default;
            return $data;
        }
        if (!isset($data[$propertyName]) && $default === null) {
            $message = (empty($message)) ? sprintf('%s must exist!', $propertyName) : $message;
            throw new ValidatorException($message);
        }
        $value = $data[$propertyName];
        if ($strict) {
            if (is_int($value)) {
                $data[$propertyName] = (int)$value;
                return $data;
            }
        } else {
            $value = $value ?: 0;
            $value = filter_var($value, FILTER_VALIDATE_INT);
            if ($value !== false) {
                $data[$propertyName] = $value;
                return $data;
            }
        }

        $message = (empty($message)) ? sprintf('%s must int!', $propertyName) : $message;
        throw new ValidatorException($message);
    }
}
