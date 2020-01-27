<?php

if (!function_exists('escape_comma')) {
    function escape_comma($str): string
    {
        return str_replace(",", "\,", $str);
    }
}

if (!function_exists('natural_implode')) {
    function natural_implode(array $arr): string
    {
        if (count($arr) <= 1) {
            return implode(', ', $arr);
        }

        $lastItem = array_pop($arr);
        return implode(', ', $arr) . ' & ' . $lastItem;
    }
}

if (!function_exists('natural_implode_unique')) {
    function natural_implode_unique(array $arr): string
    {
        return natural_implode(array_unique($arr));
    }
}

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string ...$needles): bool
    {
        foreach ($needles as $needle) {
            $length = strlen($needle);
            if (substr($haystack, 0, $length) === $needle) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string ...$needles): bool
    {
        foreach ($needles as $needle) {
            $length = strlen($needle);
            if (substr($haystack, -$length) === $needle) {
                return true;
            }
        }

        return false;
    }
}
