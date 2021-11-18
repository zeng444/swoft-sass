<?php declare(strict_types=1);

namespace App\Common\Excel\Rule;

/**
 * Class IntegerRole
 * @author Robert
 * @package App\Common\Excel\Rule
 */
class IntegerRole implements RuleInterface
{
    /**
     * @param $input
     * @param $fileType
     * @param $column
     * @param $row
     * @return mixed
     * @author Robert
     */
    public function format($input, $fileType, $column, $row)
    {
        if(!$input){
            return 0;
        }
        return intval($input);
    }
}
