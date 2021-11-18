<?php declare(strict_types=1);

namespace App\Validator\Rule;

use DateTime;
use Swoft\Bean\Annotation\Mapping\Bean;
use App\Annotation\Mapping\Date;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class DateRule
 *
 * @Bean(Date::class)
 */
class DateRule implements RuleInterface
{

    /**
     * @param array $data
     * @param string $propertyName
     * @param object $item
     * @param null $default
     * @param false $strict
     * @return array
     * @throws ValidatorException
     * @author Robert
     */
    public function validate(array $data, string $propertyName, $item, $default = null, $strict = false): array
    {
        $value = $data[$propertyName];
        if($item->getAllowEmpty() && !$value){
            return $data;
        }
        if (is_string($value)) {
            $dt = DateTime::createFromFormat($item->getFormat(), $value);
            if ($dt !== false && !array_sum($dt::getLastErrors())) {
                return $data;
            } elseif (ctype_digit($value)) {
                $date = date($item->getFormat(), (int)$value);
                if ($date) {
                    $data[$propertyName] = $date;
                    return $data;
                }
            }
        } elseif (filter_var($value, FILTER_VALIDATE_INT)) {
            if ($value >= PHP_INT_MIN && $value <= PHP_INT_MAX) {
                return $data;
            }
        }
        /* @var Date $item */
        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s must date!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

}
