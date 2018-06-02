<?php

namespace Luna\validators;


use Luna\core\Validator;

class inputValidator extends Validator
{

    /*
     *  validation
     */

    public static function min_val($num, $min){ return $num >= $min; }

    public static function max_val($num, $max){ return $num <= $max; }

    public static function min_length($text, $len){ return strlen($text) >= $len;  }

    public static function max_length($text, $len){ return strlen($text) <= $len;  }

    public static function email( $email ){ return filter_var($email, FILTER_VALIDATE_EMAIL )? true : false ; }

    public static function url( $email ){ return filter_var($email, FILTER_VALIDATE_URL )? true : false ; }

    public static function ip( $email ){ return filter_var($email, FILTER_VALIDATE_IP )? true : false ; }

    public static function mac( $email ){ return filter_var($email, FILTER_VALIDATE_MAC )? true : false ; }

    public static function equal($num1, $num2){ return $num1 === $num2; }

    public static function not_equal($num1, $num2){ return $num1 !== $num2; }

    public static function between($num1,array $range){ return $num1 >= $range[0] && $num1 <= $range[1]; }

    public static function not_between($num1,array $range){ return $num1 < $range[0] || $num1 > $range[1]; }

    public static function not_empty($num1){ return $num1 != null ; }







    /*
     *  sanitize
     */

    public static function sanitize_email( $email ){ return filter_var($email, FILTER_SANITIZE_EMAIL); }

    public static function sanitize_special_chars( $string ){ return filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS); }

    /*
     *  dealing with forms
     */

    public static function form(array $form, array $conditions)
    {
        $errs = [] ;

        foreach ($conditions as $item => $values)
        {
            foreach ($values as $value => $key )
            {
                switch ($value )
                {
                    case 'min_val' :  (! self::min_val( $form[$item], $key ) ) ? $errs[] =  $item .': value must be superior to ' . $key : null ; break;
                    case 'max_val' :  (! self::max_val( $form[$item], $key ) ) ? $errs[] =  $item .': value must be lower to ' . $key : null ; break;
                    case 'min_len' :  (! self::min_length( $form[$item], $key ) ) ? $errs[] =  $item .': the length must be superior to ' . $key : null ; break;
                    case 'max_len' :  (! self::max_length( $form[$item], $key ) ) ? $errs[] =  $item .': the length must be lower to ' . $key : null ; break;
                    case 'equal' :  (! self::equal( $form[$item], $form[$key]) ) ? $errs[] =  $item .' | ' . $key . ': are not equals' : null ; break;
                    case 'not equal' :  (! self::not_equal( $form[$item], $form[$key]) ) ? $errs[] =  $item .' | ' . $key . ': are equals' : null ; break;
                    case 'between' :  (! self::between( $form[$item], $key) ) ? $errs[] =  $item .': is not between' : null ; break;
                    case 'not between' :  (! self::not_between( $form[$item], $key) ) ? $errs[] =  $item .': is between between' : null ; break;
                    case 'verify':
                        switch ($key){
                            case 'not empty' :  ( self::not_empty( $form[$item]) ) ? $errs[] =  $item .': is empty' : null ; break;
                            case 'email' :  (! self::email( $form[$item]) ) ? $errs[] =  $item .': not valid email' : null ; break;
                            case 'url' :  (! self::url( $form[$item]) ) ? $errs[] =  $item .': not valid url' : null ; break;
                            case 'ipv4' :  (! self::ip( $form[$item]) ) ? $errs[] =  $item .': not valid ipv4 address' : null ; break;
                            case 'ip' :  (! self::ip( $form[$item]) ) ? $errs[] =  $item .': not valid ip address' : null ; break;
                            case 'mac' :  (! self::mac( $form[$item]) ) ? $errs[] =  $item .': not valid mac address' : null ; break;
                        }

                }
            }
        }
        if (empty($errs))
            return ['status' => true];
        else
            return [ 'status' => true, 'errors' => $errs ];
    }
}