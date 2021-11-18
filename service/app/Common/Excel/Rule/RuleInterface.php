<?php


namespace App\Common\Excel\Rule;


interface RuleInterface
{
    public function format($input, $fileType, $column, $row);
}
