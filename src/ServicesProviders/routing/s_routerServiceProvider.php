<?php

namespace Luna\Service\Routing\simple;

use Luna\core\ServiceProvider;
use Luna\Helpers\Loader;

class Router extends ServiceProvider
{
    private static $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * @return array
     */
    public static function getRoots()
    {
        return self::$routes;
    }

    /**
     * add a new roote
     * @param $url
     * @param string $controller
     */
    public static function add($url, $controller = '', $request_method = null)
    {
        $controller = ($controller == '')? $url : $controller;

        if ($request_method == null)
        {
            self::$routes['GET'] = array_merge(self::$routes['GET'], array_fill_keys([$url],$controller));

            self::$routes['POST'] = array_merge(self::$routes['POST'], array_fill_keys([$url],$controller));
        }

        elseif (in_array($request_method,['POST', 'post', 'GET', 'get']))
        {
            if ($request_method == 'get' || $request_method == 'GET')

                self::$routes['GET'] = array_merge(self::$routes['GET'], array_fill_keys([$url],$controller));

            elseif ($request_method == 'post' || $request_method == 'POST')

                self::$routes['POST'] = array_merge(self::$routes['POST'], array_fill_keys([$url],$controller));
        }

        else
            echo "<b>Error!</b> '$request_method' is not a valid request type.<br>";
    }

    /**
     *  checking if the route send exist or not.
     * @param $roote
     * @param $method
     * @return bool
     */
    public static function is_Root($roote, $method)
    {
        if ($method == 'get' || $method == 'GET')

            return array_key_exists($roote, self::$routes['GET']);

        elseif ($method == 'post' || $method == 'POST')

            return array_key_exists($roote, self::$routes['POST']);
    }

    /**
     *  printing the routes
     */
    public static function print_routes()
    {
        print_r(self::$routes);
    }

    public static function get_controller($url, $method)
    {
        return self::$routes[$method][$url];
    }


    /**
     * @param $url
     * @param $r_method
     * @param null $err
     */
    public static function url_match($url, $r_method, $err = null)
    {
        $controller = "HomeController" ;

        $method = "index" ;

        $param = [] ;

        $url = self::parse_url($url);

        Loader::controller("home");

        if (!isset($url[0]) ||  $url[0] == "" ) {}

        elseif ( self::is_Root($url[0], $r_method) )
        {
            $controller = self::get_controller($url[0], $r_method) ;

            unset($url[0]);

            Loader::controller($controller);

            $controller =  $controller . "Controller";

            if( isset($url[1]) && method_exists( $controller , $url[1]) )
            {
                $method = $url[1];

                unset($url[1]);
            }

        }
        elseif ( method_exists("homeController" , $url[0]) )
        {
            $method = $url[0];

            unset($url[0]);
        }

        else
        {
            Loader::html("notfound");// or "home if you want it to redirect to home controller

            die();
        }
        $param = $url ? array_values($url) : [];

        call_user_func( [$controller , 'init'], $param);

        call_user_func( [$controller , $method], $param);

        die();
    }

    private static function parse_url($url)
    {
        if(isset($url))
        {
            return $url = explode('/' ,  filter_var( rtrim($url, '/') , FILTER_SANITIZE_URL) );
        }
    }
}