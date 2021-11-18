<?php


namespace App\Common\Excel\Rule;

/**
 * Class StringRole
 * @author Robert
 * @package App\Common\Excel\Rule
 */
class StringRole implements RuleInterface
{

    /**
     * @param $input
     * @param $fileType
     * @param $column
     * @param $row
     * @return string
     * @author Robert
     */
    public function format($input, $fileType, $column, $row)
    {
        return (string)$input;
    }
}
