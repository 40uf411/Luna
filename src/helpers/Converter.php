<?php

namespace Luna\helpers;


class Converter
{
    /*
     | ------------------------------
     |      treat the time
     | ------------------------------
     | this function takes a string
     | and return the result in
     | seconds.
     | EX: 5d (5 days), 26h (26 hours) ...
     |
     | s => seconds
     | m => minuets
     | h => hours
     | d => days
     | w => weeks
     | M => months
     | y => years
     | ------------------------------
     */
    public static function treat_time($time)
    {
        if ( is_string( $time ))
        {
            $char = substr( $time, -1 );

            $t =  intval(substr($time, 0, -1)) ;

            switch ($char){
                case 's': break;
                case 'm': $t *= 60; break;
                case 'h': $t *= 60*60; break;
                case 'd': $t *= 60*60*24; break;
                case 'w': $t *= 60*60*24*7; break;
                case 'M': $t *= 60*60*24*30; break;
                case 'y': $t *= 60*60*24*365; break;
                default : break;
            }
            return $t;
        }
        return $time;
    }

    public static function render_string($number)
    {
        
    }
}