<?php

if (!function_exists('escape_comma')) {
    function escape_comma($str) : string {
        return str_replace(",", "\,", $str);
    }
}
