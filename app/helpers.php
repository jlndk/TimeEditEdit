<?php

if(!function_exists('escape_comma')) {
    function escape_comma($str) : string {
        str_replace(",","\,",$str)
    }
}
