<?php

namespace Luna\services;

use Luna\Core\Service;
use Luna\Helpers\Loader;
use Luna\services\Router\Route;

class Router extends Service
{
    public static $request_methods = ['get', 'post', 'head', 'put', 'delete'];

    public static $routes = [];

    public static function init($info = null)
    {
        parent::init($info);

        require_once "Route.php";
    }

    public static function config($info = null)
    {
        parent::config($info);

        Loader::config('services' . DS . "routes" );
    }

    public static function home($callback = null)
    {
        $cb = self::split_cb($callback);

        $r = new Route($cb[0],$cb[1]);

        $r->url('/');

        self::$routes['/'] = $r;

        return $r;
    }

    public static function get($url, $callback = null)
    {
        $cb = self::split_cb($callback);

        $r = new Route($cb[0],$cb[1]);

        $r->url($url);

        self::$routes['get'][$url] = $r;

        return $r;
    }

    public static function post($url, $callback = null)
    {
        $cb = self::split_cb($callback);

        $r = new Route($cb[0],$cb[1]);

        $r->url($url);

        $r->middleware('token');

        self::$routes['post'][$url] = $r;

        return $r;
    }

    public static function head($url, $callback = null)
    {
        $cb = self::split_cb($callback);

        $r = new Route($cb[0],$cb[1]);

        $r->url($url);

        self::$routes['head'][$url] = $r;

        return $r;
    }

    public static function put($url, $callback = null)
    {
        $cb = self::split_cb($callback);

        $r = new Route($cb[0],$cb[1]);

        $r->url($url);

        self::$routes['put'][$url] = $r;

        return $r;
    }

    public static function delete($url, $callback = null)
    {
        $cb = self::split_cb($callback);

        $r = new Route($cb[0],$cb[1]);

        $r->url($url);

        self::$routes['delete'][$url] = $r;

        return $r;
    }

    public static function redirect($url, $callback, $time = 3)
    {
        $r = new Route("redirect", $callback , $time);

        self::$routes['redirect'][$url] = $r;

        return $r;
    }

    public static function any($url, $callback = null)
    {
        $cb = self::split_cb($callback);

        $r = new Route($cb[0],$cb[1]);

        $r->url($url);

        self::$routes['get'][$url] = $r;
        self::$routes['post'][$url] = $r;
        self::$routes['head'][$url] = $r;
        self::$routes['put'][$url] = $r;
        self::$routes['delete'][$url] = $r;

        return $r;
    }

    public static function match(array $methods, $url, $callback= null)
    {
        $cb = self::split_cb($callback);

        $r = new Route($cb[0],$cb[1]);

        $r->url($url);


        foreach ($methods as $method)
        {
            $method = strtolower($method);
            if (in_array( $method ,self::$request_methods ))
            {
                self::$routes[$method][$url] = $r;
            }
        }
        return $r;
    }


    public static function matchUrl($url, $r_method = "get")
    {
        // in case of no route add an error and stop immediately
        if (empty(self::$routes))
        {
            ErrorHandler::add('NoRouteFound', true);
        }
        // in case of no home route add an error and stop immediately
        elseif ( ! isset(self::$routes['/']))
        {
            ErrorHandler::add('HomeRoueIsNotSet', true);
        }
        else
        {
            // checking if its the home route
            if ($url == null || $url == '/')
            {
                return self::$routes['/'];
            }
            elseif (is_string($url) && is_string($r_method))
            {
                // sanitizing the url

                $url = filter_var( rtrim($url , '/') , FILTER_SANITIZE_URL);

                $r_method = strtolower($r_method);

                //checking if the route exists in the redirect routes
                if(isset(self::$routes['redirect']) && isset(self::$routes['redirect'][$url]))
                {
                    return self::$routes['redirect'][$url];
                }
                //checking if a route with the same url exists
                elseif(isset(self::$routes[$r_method]))
                {
                    if ( isset(self::$routes[$r_method][$url]) )
                    {
                        return self::$routes[$r_method][$url];
                    }
                    else
                    {
                        // exploding the url
                        $url = explode('/', $url);

                        //walking through all th registered routes
                        foreach (self::$routes[$r_method] as $dist => $route)
                        {
                            // exploding the potential route
                            $tmp = explode('/', filter_var( rtrim($dist , '/') , FILTER_SANITIZE_URL) );

                            // testing the equality
                            if ( count($url) === count($tmp) )
                            {
                                // rolling through the parts of the route
                                for ($i = 0; $i < count($url); $i++)
                                {
                                    if ($i > 0)
                                    {

                                        // testing if the element is an integer and if so its ganna be saved in $data[]
                                        if (substr($tmp[$i],0,6) === "$(int)" )
                                        {
                                            if ( $url[$i] == "0" || intval($url[$i]) != "0")
                                            {
                                                if($route->getPattern(str_replace('$(int)','', $tmp[$i])))
                                                {
                                                    $pat = $route->getPattern(str_replace('$(int)','', $tmp[$i]));
                                                    $wrd = $url[$i] ;
                                                    if( ! self::check_pattern($wrd,$pat))
                                                    {
                                                        continue;
                                                    }
                                                }

                                                $data[str_replace('$(int)','', $tmp[$i])] =  intval($url[$i]);

                                                $tmp[$i] = $url[$i];
                                            }
                                        }

                                        // testing if the element is an float and if so its ganna be saved in $data[]
                                        elseif (substr($tmp[$i],0,8) === "$(float)" )
                                        {
                                            if ( $url[$i] == "0" || floatval($url[$i]) != "0")
                                            {
                                                if($route->getPattern(str_replace('$(int)','', $tmp[$i])))
                                                {
                                                    $pat = $route->getPattern(str_replace('$(float)','', $tmp[$i]));
                                                    $wrd = $url[$i] ;
                                                    if( ! self::check_pattern($wrd,$pat))
                                                    {
                                                        continue;
                                                    }
                                                }
                                                $data[str_replace('$(float)','', $tmp[$i])] =  floatval($url[$i]);

                                                $tmp[$i] = $url[$i];
                                            }
                                        }

                                        // testing if the element is a variable and if so its ganna be saved in $data[]
                                        elseif (strpos($tmp[$i], '$') !== false)
                                        {
                                            if($route->getPattern(str_replace('$','', $tmp[$i])))
                                            {
                                                $pat = $route->getPattern(str_replace('$','', $tmp[$i]));
                                                $wrd = $url[$i] ;
                                                if( ! self::check_pattern($wrd,$pat))
                                                {
                                                    continue;
                                                }
                                            }
                                            $data[str_replace('$','', $tmp[$i])] =  $url[$i];

                                            $tmp[$i] = $url[$i];
                                        }
                                    }
                                } // end for;

                                // checking if this is the correct route
                                if (count(array_diff($tmp, $url)) === 0)
                                {
                                    return [self::$routes[$r_method][$dist] ,$data];
                                }
                            } // end if count;

                        }
                    }
                }
                else
                {
                    ErrorHandler::add("NoRouteForThisRequestMethod");
                }

            }
        }
    }

    private static function split_cb($cb)
    {
        if ( is_callable($cb) )
        {
            return["function", $cb];
        }
        elseif (is_string($cb))
        {
            $cb = explode('@', $cb);

            if( isset($cb[1]))
            {
                return [
                    'controller_method' ,
                    [
                        'controller' =>$cb[0],
                        'method' => $cb[1]
                    ]
                ];
            }
            else
            {
                return[
                  'controller' ,
                  $cb
                ];
            }
        }
    }

    private static function check_pattern($word, $pattern)
    {
        return preg_match($pattern, $word);
    }
}