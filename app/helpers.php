<?php

if (!function_exists('escape_comma')) {
    function escape_comma($str) : string
    {
        return str_replace(",", "\,", $str);
    }
}

if (!function_exists('natural_implode')) {
    function natural_implode(array $arr) : string
    {
        if (count($arr) <= 1) {
            return implode(', ', $arr);
        }

        $lastItem = array_pop($arr);
        return implode(', ', $arr) . ' & ' . $lastItem;
    }
}

if (!function_exists('natural_implode_unique')) {
    function natural_implode_unique(array $arr) : string
    {
        return natural_implode(array_unique($arr));
    }
}
