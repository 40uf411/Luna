<?php

namespace Luna\Core;

use Luna\Helpers\Loader;
use Luna\Service\Routing\simple\Router as SimpleRouter;
use Luna\ServiceProvider\Cookies;
use Luna\ServiceProvider\Files;
use Luna\ServiceProvider\Logger;
use Luna\ServiceProvider\Sessions;
use Luna\services\Routing\advanced\Router as Router;


class App
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
        ob_start();

        define("REQUEST_LAUNCH_TIME", time() );

        $this->set_con();

        $this->init();

        ini_set('date.timezone', APP_TIMEZONE);

        ini_set('sendmail_from', APP_DEFAULT_EMAIL);

        ini_set('smtp_port', APP_DEFAULT_STMP_PORT);

        if (APP_ENV === "offline")
        {
            Loader::html("offline-app");
        }
        else
        {
            $url = isset($_GET['url'])? $_GET['url']: null;

            if (ROUTING_SYS === "simple")

                SimpleRouter::url_match( $url, $_SERVER['REQUEST_METHOD']);

            elseif (ROUTING_SYS === "advanced")

                Router::url_match($url, $_SERVER['REQUEST_METHOD']);

            else

                echo "System routing configurations error! ";
        }

        ob_end_flush();

        die();
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

        require_once "../src/helpers/Loader.php";

        Loader::config("core/php");

        // Loading the basic abstract classes

        Loader::folder("abstracts", require_once "../src/config/abst.php");

        // Loading the services

        Loader::folder("providers", require_once "../src/config/sp.php");

        Loader::folder("validators", ['inputs validator' => 'input']);

        // Loading the configurations files

        Loader::config('core'. DS .'routes');

        //

        Loader::helper("Converter");

        // initialization of sessions

        Sessions::init(true);

        Cookies::config();

        Logger::config();

        Files::config();


        // Loading all the packages

        Loader::vendor();

        // Loading the build in libraries

        Loader::lib();
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

        require_once "../src/config/dir.config.php";

        $datas = require_once CONFIG_PATH . "core". DS ."web.config.php";

        foreach ($datas as $data => $value)

            define($data, $value);

        // logging levels

        define('EMERGENCY_LEVEL', 0 );
        define('ERROR_LEVEL', 1 );
        define('CRITICAL_LEVEL', 2 );
        define('ALERT_LEVEL', 3 );
        define('WARING_LEVEL', 4 );
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


}