<?php

namespace Luna\lib\artista;

use Luna\lib\artista\src\Html as html;

class Artista
{
    public static function init()
    {
        require_once "src/html.php";
    }


    public static function tag($name , $body, array $attributes = null)
    {
        $tag = "<$name ";

        if ($attributes)

            foreach ($attributes as $attribute => $value)

                $tag = $tag . $attribute . "=" . $value ;

        return $tag =  $tag .">"  . $body . "</$name>";
    }

    public static function html($page = null)
    {
        return "<!DOCTYPE html> $page </html>";
    }

    public static function head($core = null)
    {
        return "<head> $core </head>";
    }
    public static function title($core)
    {
        return "<title> $core </title>";
    }
    public static function meta($core)
    {
        return "<meta $core >";
    }
    public static function body($core = null)
    {
        return "<body> $core </body>";
    }

    public static function loop($code, $times)
    {
        for( $i = 0 ; $i < $times ; $i++ )

            $code ;

        return $code;
    }
}