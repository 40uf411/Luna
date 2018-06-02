<?php

namespace Luna\Helpers;

class Loader{


    //

    public static function folder($folder, array $files)
    {

        switch ($folder)
        {
            case "abstracts" : $fun = 'abst'; break;
            case "providers" : $fun = 'provider'; break;
            case "configs" : $fun = 'config'; break;
            case "validators" : $fun = 'validator'; break;
        }
        if (isset($fun))
        {
            foreach ($files as $file => $key)
            {
                self::$fun($key);
            }
        }
        else

            return FAILURE;

    }

    // Loading all the packages

    public static function vendor()
    {
        require_once VENDOR_PATH . "autoload.php";
    }


    // Load library classes

    public static function lib()
    {
        require_once LIB_PATH . "autoload.php";

    }


    // loader helper functions. Naming conversion is *Helper.php;

    public static function helper($helper)
    {
        require_once HELPER_PATH . $helper . ".php";

    }


    // loader abstract class. Naming conversion is *Provider.php;

    public static function abst($abstract)
    {
        require_once CORE_PATH . $abstract . ".php";

    }


    // loader provider class. Naming conversion is *Provider.php;

    public static function provider($provider)
    {
        require_once SERVICES_PROVIDERS_PATH . $provider . "ServiceProvider.php";

    }


    // loader controller class. Naming conversion is *Controller.php;

    public static function controller($controller)
    {
        require_once CONTROLLER_PATH . $controller . "Controller.php";

    }


    // loader model class. Naming conversion is *Model.php;

    public static function model($model)
    {
        require_once MODEL_PATH . $model . "Model.php";

    }


    // loader configuration file. Naming conversion is *.config.php;

    public static function config($config)
    {
        return require_once CONFIG_PATH . $config . ".config.php";
    }


    // loader view . Naming conversion is *.view.php;

    public static function view($view)
    {
        require_once VIEW_PATH . $view . "View.php";

    }


    //

    public static function validator($validator)
    {
        require_once VALIDATORS_PATH . $validator . "Validator.php";
    }

    //

    public static function php($name, $return = true)
    {
        if ($return)

            return HTML_PATH . "dynamic" . DS . $name . ".php";

        else

            echo HTML_PATH . "dynamic" . DS . $name . ".php";
    }


    // loader css files. Naming conversion is *.css;

    public static function html($html , $return = false, $content = true )
    {
        if ($return)

            if ($content)

                return file_get_contents(HTML_PATH . "static" . DS . $html . ".html");

            else

                return HTML_PATH . "static" . DS . $html . ".html";

        else

            if ($content)

                echo file_get_contents(HTML_PATH . "static" . DS . $html . ".html");

            else

                echo HTML_PATH . "static" . DS . $html . ".html";

    }


    // loader css files. Naming conversion is *.css;

    public static function css($css , $return = false)
    {
        if ($return)
            return CSS_URL . $css . ".css";

        else
            echo CSS_URL . $css . ".css";

    }


    // loader js file. Naming conversion is *.js;

    public static function js($js , $return = false)
    {
        if ($return)
            return JS_URL . $js . ".js";

        else
            echo JS_URL . $js . ".js";

    }


    // loader image . Naming conversion is *.*;

    public static function img($img,$type , $return = false)
    {
        if ($return)
            return IMG_URL . $img . ".$type";

        else
            echo IMG_URL . $img . ".$type";

    }


    // loader uploaded file. Naming conversion is *;

    public static function uploads($upload , $return = false)
    {
        if ($return)
            return UPLOAD_URL . $upload ;

        else
            echo UPLOAD_URL . $upload ;

    }


    // loader database class. Naming conversion is *.php;

    public static function database($db)
    {
        echo DB_PATH . $db . ".php" ;

    }
}