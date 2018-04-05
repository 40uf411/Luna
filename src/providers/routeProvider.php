<?php

namespace Luna\Providers;

class RouteProvider
{
    private static $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * initialize the main routes
     */
    public static function init()
    {
        self::add('home', 'Home');

        self::add('notFound', '404');
    }

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
}