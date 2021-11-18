<?php declare(strict_types=1);

namespace App\Common\Excel\Rule;

use PhpOffice\PhpSpreadsheet\Shared\Date;

/**
 * Class TimeRole
 * @author Robert
 * @package App\Common\Excel\Rule
 */
class TimeRole implements RuleInterface
{
    /**
     * @param $input
     * @param $fileType
     * @param $column
     * @param $row
     * @return array|false|mixed|string|string[]
     * @author Robert
     */
    public function format($input, $fileType, $column, $row)
    {
        try {
            if(!$input){
                return '';
            }
            $format = 'H:i:s';
            if (preg_match('/^[\d\.]+$/', (string)$input)) {
                $input = Date::excelToTimestamp($input, new \DateTimeZone('PRC'));
                return date($format, $input);
            }
            if (strpos($input, '/') !== false) {
                $input = str_replace('/', '-', $input);
            }
            if (strpos($input, ':') === 13) {
                return $input;
            }
            $input = strtotime($input);
            if (!ctype_digit($input)) {
                return '';
            }
            return date($format, $input);
        } catch (\Exception $e) {
            return '';
        }
    }
}
