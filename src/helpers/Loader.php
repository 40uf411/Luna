<?php

namespace Luna\Helpers;

class Loader{

    // Load library classes

    public static function library($lib)
    {
        require_once LIB_PATH . $lib . ".php";

    }


    // loader helper functions. Naming conversion is *Helper.php;

    public static function helper($helper)
    {
        require_once HELPER_PATH . $helper . "Helper.php";

    }


    // loader provider class. Naming conversion is *Provider.php;

    public static function provider($provider)
    {
        require_once PROVIDERS_PATH . $provider . "Provider.php";

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
        require_once CONFIG_PATH . $config . ".config.php";

    }


    // loader view . Naming conversion is *.view.php;

    public static function view($view)
    {
        require_once VIEW_PATH . $view . ".view.php";

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