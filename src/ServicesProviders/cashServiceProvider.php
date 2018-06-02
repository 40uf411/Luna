<?php

namespace Luna\ServicesProviders;

use Luna\core\ServiceProvider;
use Luna\ServiceProvider\Files;
use \Exception;


class Cash extends ServiceProvider
{
    public static $variables = [];

    public static function bootstrap($variable, $value, $owner = "all")
    {
        self::save($variable, $value, $owner, "globals");
    }
    
    public static function save($variable , $value, $owner = "all" , $place = "default")
    {
        $res = "$variable:$value@$owner" . PHP_EOL;

        file_put_contents(CASH_PATH . $place . ".ldf" ,$res , FILE_APPEND);
    }

    public static function load($variable , $owner = "all" , $place = "default")
    {
        if ( isset( self::$variables[ $place ] ) )
        {
            return ( isset( self::$variables[ $place ][$variable] ) )? (in_array(self::$variables[ $place ][$variable]['owner'],['all',$owner]))?self::$variables[ $place ][$variable]['value'] : false : false ;
        }
        else
        {
            if (self::load_file($place))

                return self::load($variable, $owner, $place);
        }
    }
    private static function load_file($place)
    {
        if (!Files::exists(CASH_PATH . $place . ".ldf"))

            return false;

        $v = file_get_contents(CASH_PATH . $place . ".ldf" );

        $v = explode(PHP_EOL , $v);

        foreach ($v as $t)
        {
            if ($t == "")
                break;
            $t = explode(":",$t);
            $p = explode("@",$t[1]);
            $vars[$t[0]]['value'] = $p[0];
            $vars[$t[0]]['owner'] = $p[1];
        }

        self::$variables[$place] = $vars;

        return true;
    }
}