<?php

namespace Luna\Core;


use Luna\Helpers\Loader;
use Luna\ServiceProvider\Files;

abstract class View
{
    protected static function head($data)
    {
       return "
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title>Default page</title>
</head>
       ";
    }

    protected static function body($data)
    {
        return "<body><h1>Nothing to show here.</h1></body>";
    }

    protected static function footer($data){}

    public static function launch($data = null)
    {
        if ( isset($data['classname']) && $data['classname'] != null )
        {
            if (!Files::exists(VIEW_PATH . $data['classname'] . "View.php"))
                self::error_handler(array_merge($data,[
                    "error" => "the view class '" . $data['classname'] ."' was not found, please check if the file exists and with a correct name.",
                    "load" => 'view_error',
                        ])
                );

            Loader::view($data['classname']);

            $class = $data['classname'] . "View";

            echo

                "<!doctype html>" .

                $class::head($data) .

                $class::body($data) .

                $class::footer($data)

                ."</html>";

            die();
        }
        elseif ( isset($data['php_page']) )
        {
            require_once $data['php_page'];

            exit();
        }
        elseif ( isset($data['html_page']) )
        {
            echo file_get_contents( $data['url'] );

            exit();
        }
       elseif ( isset($data['template']) )
        {
            echo $data['template'];

            die();
        }
        elseif ( isset($data['head']) && isset($data['body']) )
        {
            echo "<!doctype html>" . $data['head'] . $data['body'] ;
            echo ( isset($data['footer'])) ? $data['footer'] : null . "</html>";

            die();
        }
        else
        {
            echo

                "<!doctype html>" .

                self::head($data) .

                self::body($data) .

                self::footer($data)

                ."</html>";

            die();
        }
    }

    protected static function error_handler($stat)
    {
        if (isset($stat['on_error']))
        {
            echo $stat['on_error'];
        }
        else
        {
            if (isset($stat['load']))
            {
                require_once Loader::php($stat['load']);
            }

            else
            {
                echo $stat['load'];
            }
        }


        die();
    }


}