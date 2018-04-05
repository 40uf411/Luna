<?php

namespace Luna\Core;

use Luna\Providers\RouteProvider ;
use Luna\Providers\HttpProvider ;
use Luna\Providers\SessionProvider ;
use Luna\Providers\CookiesProvider ;
use Luna\Providers\DatabaseProvider ;
use Luna\Helpers\Loader;
use Luna\Providers\Log ;


class App
{
    public $controller = "Home" ;

    protected $method = "index" ;

    protected $param = [] ;

    public function __construct()
    {
        ob_start();

        $this->init();

        $url = $this->parseUrl();

        $this->dispatch($url);

        ob_end_flush();
    }


    private function init(){

        // Loading the web configurations loader and the log

        require_once "..\config\dir.config.php";

        require_once "..\src\helpers\Loader.php";

        //Loader::config("php");

        // Loading the Log provider

        Loader::provider('log');

        Loader::config('log');


        // Loading the routing provider and its configurations

        Loader::provider("route");

        RouteProvider::init();

        Loader::config("routes");


        // Loading the database provider and its configuration

        Loader::provider("Database");

        Loader::config("database");


        // Loading the Http provider

        Loader::provider("Http");


        //  Loading the session provider and its configuration

        Loader::provider('session');

        SessionProvider::init(false);


        // Loading the cookies provider and its configuration

        Loader::provider("cookies");

        Loader::config("cookies");


        // Loading the cookies provider and its configuration

        Loader::provider("files");

        Loader::config("files");


        // Loading the Controller and Model abstract classes

        require_once "Controller.php";

        require_once "Model.php";
    }


    private function parseUrl(){

        if(isset($_GET['url'])){

            return $url = explode('/' ,  filter_var( rtrim($_GET['url'] , '/') , FILTER_SANITIZE_URL) );

        }
    }

    private function dispatch($url)
    {

        if (!isset($url[0]) ||  $url[0] == "" )

            $contr = "home";

        elseif ( RouteProvider::is_Root($url[0], $_SERVER['REQUEST_METHOD']) )

            $contr = routeProvider::get_controller($url[0], $_SERVER['REQUEST_METHOD']) ;

        else

            $contr = "notFound";


        if ($contr == "notFound")
        {
            echo "not found";

            die();
        }

        else
        {
            Loader::controller($contr);

            $contr =  $contr . "Controller";

            $this->controller = new $contr;

            unset($url[0]);
        }

        if(isset($url[1]) && method_exists($this->controller , $url[1]))
        {
            $this->method = $url[1];

            unset($url[1]);
        }
        else
        {
            $this->method = 'index';
        }

        $this->param = $url ? array_values($url) : [];

        call_user_func([$this->controller , $this->method] , $this->param);
    }
}