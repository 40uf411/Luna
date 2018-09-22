<?php

namespace Luna\Core;

use Luna\Helpers\Loader;

use Luna\services\{
    Console, ErrorHandler, Router as Router, Timer, View, Schedule, Http\Request, Http\Response
};

class Luna
{

    /*
     |--------------------------------------
     |
     |      # Luna Constructor #
     |
     |--------------------------------------
     |
     |  Here's where everything starts,
     |
     |  Rout dispatching, setting constants,
     |
     |  including all the classes, libraries...
     |
     */

     public function __construct()
    {
        define("REQUEST_LAUNCH_TIME", time() );

        $this->set_con();

        $this->init();
    }

    /**
     * @param null $arg
     * @throws \Error
     * @throws \Exception
     */
    public function launch($arg = null)
    {
        if (! is_cli())
            ob_start();

        Schedule::execute(null, (new Timer\Time())->now() );

        if (php_sapi_name() === 'cli')
        {
            Console::launch($arg);
        }
        else
        {
            $query = str_replace(APP_SHORT_URL , '', $_SERVER['REQUEST_URI']) ;


            $url = isset($_GET['url']) ? '/' . $_GET['url']: $query;
            $url = explode("?",$url)[0];

            $request = Request::instance($_SERVER['REQUEST_METHOD']);
            $response = Response::instance();

            $GLOBALS["request"] = $request;
            $GLOBALS["response"] = $response;

            $route =  Router::matchUrl( $url , $request->method());

            $output = ($route != null)? $this->treat_route($route, $request,$response) : '<br>no route found';

            $response->flush();

            echo $output;
        }

        ErrorHandler::run();

        if (!is_cli())
            ob_flush();
    }

    /*
     |--------------------------------------
     |
     |     # The framework assembler #
     |
     |--------------------------------------
     |
     |  The job of this function is to
     |
     |  include all the needed classes, libs...
     |
     */

    private function init()
    {
        // Loading the web configurations loader and the log
        if (php_sapi_name() === 'cli')
        {
            require_once "src/helpers/Loader.php";

            // Loading all the packages

            Loader::vendor();

            require_once "src/helpers/functions.php";

            Loader::config("core/php");

            // Loading the basic abstract classes
            Loader::folder("core", require_once "src/config/core_classes.php");

            // Loading the services
            $services = require_once "src/config/services.php";
        }
        else
        {
            require_once "../src/helpers/Loader.php";

            // Loading all the packages

            Loader::vendor();

            require_once "../src/helpers/functions.php";

            Loader::config("core/php");

            // Loading the basic abstract classes
            Loader::folder("core", require_once "../src/config/core_classes.php");

            // Loading the services
            $services = require_once "../src/config/services.php";
        }

        foreach ($services as $service => $value)
        {
            if (!(php_sapi_name() === 'cli' and !$value['cli']))
            {
                Loader::service($value["location"]);
            }
        }

        // Loading the build in libraries

        Loader::lib();

        foreach ($services as $service => $value)
        {
            if (!(php_sapi_name() === 'cli' and !$value['cli']))
            {
                if ( $value['init'] == true )

                    $service::init($value['init_pram']);

                if ( $value['config'] == true )

                    $service::config($value['config_pram']);
            }
        }

    }


    /*
     |--------------------------------------
     |
     |      # Constant machine #
     |
     |--------------------------------------
     |
     |  this part is the responsible of making
     |
     |  all the basic framework constants,
     |
     |  and including the others that are in an
     |
     |  external file.
     |
     */

    private function set_con()
    {

        if (php_sapi_name() === 'cli')
            require_once "src/config/dir.config.php";
        else
            require_once "../src/config/dir.config.php";

        $datas = require_once CONFIG_PATH . "core". DS ."app.config.php";

        foreach ($datas as $data => $value)

            define($data, $value);


        # array sorting modes

        define('L_SORT_ASC',  0);
        define('L_SORT_DESC',  1);
        define('L_SORT_ASC_VALUE',  2);
        define('L_SORT_ASC_KEY',  3);
        define('L_SORT_DESC_VALUE',  4);
        define('L_SORT_DESC_KEY',  5);

        // logging levels

        define('EMERGENCY_LEVEL', 0 );
        define('ERROR_LEVEL', 1 );
        define('CRITICAL_LEVEL', 2 );
        define('WARING_LEVEL', 3 );
        define('ALERT_LEVEL', 4 );
        define('NOTICE_LEVEL', 5 );
        define('INFO_LEVEL', 6 );
        define('DEBUG_LEVEL', 7 );

        // status constants

        define('SUCCESS', uniqid(APP_DEFAULT_PREFIX));
        define('FAILURE', uniqid(APP_DEFAULT_PREFIX));

        define('ON', uniqid(APP_DEFAULT_PREFIX));
        define('OFF', uniqid(APP_DEFAULT_PREFIX));

        define('ACTIVE', uniqid(APP_DEFAULT_PREFIX));
        define('NOT_ACTIVE', uniqid(APP_DEFAULT_PREFIX));

    }


    private function treat_route( $route, Request $request ,Response $response)
    {
        if (is_array($route))
        {
            $data = $route[1];

            $route = $route[0];
        }
        else
        {
            $data = [];
        }

        if (Middleware::handle($request,$route->getMiddleware()))
        {
            switch ($route->getAction())
            {
                case 'redirect':


                    header("Refresh: " . $route->getTime() . "; url="  . $route->getCallback() );

                    return "Redirecting in " . $route->getTime() . " seconds ...";

                    break;

                case 'function':

                    return $route->getCallback()($data, $request, $response);

                    break;

                case 'view':

                    return view('index', $data, $route->getDriver());

                    break;

                case 'controller':

                    $controller = $route->getCallback()[0];

                    Loader::controller($controller);

                    $controller = $controller . "Controller";

                    $controller = new $controller;

                    return $controller($data, $request, $response);

                    break;

                case 'controller_method':

                    $controller = $route->getCallback()["controller"];

                    $method = $route->getCallback()["method"];

                    Loader::controller($controller);

                    $controller = $controller . "Controller";

                    $controller = new $controller;

                    return $controller->$method($data, $request, $response);

                    break;

                default :

                    return "no callback method to execute";

                    break;
            }
        }
        else
        {
            echo "";
        }
    }

}