<?php

namespace Luna\lib\speller;

require_once "toString.php";
require_once "toNum.php";

class Speller
{
    public static function render_string($number)
    {
        if (is_integer($number))

            return toString::treat($number);

        else

            return false;
    }

    public static function render_number($string)
    {

    }
}