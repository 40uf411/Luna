<?php

namespace Luna\services\Routing\advanced;

use Luna\core\ServiceProvider;
use Luna\Helpers\Loader;

class Router extends ServiceProvider
{
    private static $GET = [];
    private static $POST = [];
    private static $PUT = [];
    private static $DELETE = [];
    private static $HEAD = [];

    private static $REDIRECT = [];

    public static function redirect($from, $to,$time)
    {
        self::$REDIRECT[$from]['call_type'] = 'redirect';

        self::$REDIRECT[$from]['to'] = $to;

        self::$REDIRECT[$from]['time'] = $time;
    }
    /**
     * @param $url
     * @param $controller_method
     */
    public static function get($url, $controller_method)
    {
        if(is_callable($controller_method))
        {
            self::$GET[$url]['call_type'] = 'function';

            self::$GET[$url]['function'] = $controller_method;
        }
        else
        {
            if (is_string($controller_method))
            {
                $controller_method = explode('@', $controller_method);

                $controller = $controller_method[1];

                $method = $controller_method[0];

                self::$GET[$url]['call_type'] = 'controller/method';

                self::$GET[$url]['controller'] = $controller;

                self::$GET[$url]['method'] = $method;
            }
        }
    }

    /**
     * @param $url
     * @param $controller_method
     */
    public static function post($url, $controller_method)
    {
        if(is_callable($controller_method))
        {
            self::$POST[$url]['call_type'] = 'function';

            self::$POST[$url]['function'] = [$controller_method];
        }
        else
        {
            if (is_string($controller_method))
            {
                $controller_method = explode('@', $controller_method);

                $controller = $controller_method[1];

                $method = $controller_method[0];

                self::$POST[$url]['call_type'] = 'controller/method';

                self::$POST[$url]['controller'] = $controller;

                self::$POST[$url]['method'] = $method;
            }
        }
    }

    /**
     * @param $url
     * @param $controller_method
     */
    public static function put($url, $controller_method)
    {
        if(is_callable($controller_method))
        {
            self::$PUT[$url]['call_type'] = 'function';

            self::$PUT[$url]['function'] = [$controller_method];
        }
        else
        {
            if (is_string($controller_method))
            {
                $controller_method = explode('@', $controller_method);

                $controller = $controller_method[1];

                $method = $controller_method[0];

                self::$PUT[$url]['call_type'] = 'controller/method';

                self::$PUT[$url]['controller'] = $controller;

                self::$PUT[$url]['method'] = $method;
            }
        }
    }

    /**
     * @param $url
     * @param $controller_method
     */
    public static function delete($url, $controller_method)
    {
        if(is_callable($controller_method))
        {
            self::$DELETE[$url]['call_type'] = 'function';

            self::$DELETE[$url]['function'] = [$controller_method];
        }
        else
        {
            if (is_string($controller_method))
            {
                $controller_method = explode('@', $controller_method);

                $controller = $controller_method[1];

                $method = $controller_method[0];

                self::$DELETE[$url]['call_type'] = 'controller/method';

                self::$DELETE[$url]['controller'] = $controller;

                self::$DELETE[$url]['method'] = $method;
            }
        }
    }

    /**
     * @param $url
     * @param $controller_method
     */
    public static function head($url, $controller_method)
    {
        if(is_callable($controller_method))
        {
            self::$HEAD[$url]['call_type'] = 'function';

            self::$HEAD[$url]['function'] = [$controller_method];
        }
        else
        {
            if (is_string($controller_method))
            {
                $controller_method = explode('@', $controller_method);

                $controller = $controller_method[1];

                $method = $controller_method[0];

                self::$HEAD[$url]['call_type'] = 'controller/method';

                self::$HEAD[$url]['controller'] = $controller;

                self::$HEAD[$url]['method'] = $method;
            }
        }
    }

    /**
     * @param $url
     * @param $controller_method
     */
    public static function any($url, $controller_method)
    {
        self::get($url, $controller_method);
        self::post($url, $controller_method);
        self::head($url, $controller_method);
        self::delete($url, $controller_method);
    }

    /**
     * @param array $request_methods
     * @param $url
     * @param $controller_method
     */
    public static function match(array $request_methods ,$url, $controller_method)
    {
        foreach ($request_methods as $method)
        {
            $method = strtolower($method);

            if (in_array($method,['get','post','delete','head']))

                self::$method($url, $controller_method);
        }
    }

    /**
     * @return array
     */
    public static function getGET()
    {
        return self::$GET;
    }

    /**
     * @return array
     */
    public static function getPOST()
    {
        return self::$POST;
    }

    /**
     * @return array
     */
    public static function getPUT()
    {
        return self::$PUT;
    }

    /**
     * @return array
     */
    public static function getDELETE()
    {
        return self::$DELETE;
    }

    /**
     * @return array
     */
    public static function getHEAD()
    {
        return self::$HEAD;
    }


    /***************************************
     *
     *  matching the url to a certain rout.
     *
     ***************************************/

    public static function url_match($url, $r_method, $err = null)
    {
        $data = null;

        if (isset($url) and $url != null)
        {
            if (is_string($url) && is_string($r_method))
            {
                $url = filter_var( rtrim($url , '/') , FILTER_SANITIZE_URL);

                $r_method = strtoupper($r_method);

                // in case a rout with the same url's exists.
                if(array_key_exists($url, self::$REDIRECT))
                {
                    echo "Redirecting! please wait...";

                    header("Refresh: " . APP_DEFAULT_REDIRECT_TIME . "; url="  . APP_URL . self::$REDIRECT[$url]['to'] );


                    die();

                }
                elseif (array_key_exists($url, self::$$r_method))
                {
                    if (  self::$$r_method[$url]['call_type'] == 'function'  )
                    {
                        if (  is_callable(self::$$r_method[$url]['function'])  )
                        {

                            self::$$r_method[$url]['function']();

                            die();
                        }
                        else
                            die("couldn't call the function");
                    }
                    elseif (  self::$$r_method[$url]['call_type'] == 'controller/method' )
                    {
                        if (file_exists(CONTROLLER_PATH . self::$$r_method[$url]['controller'] . "Controller.php") )
                        {
                            Loader::controller(self::$$r_method[$url]['controller']);

                            call_user_func([
                                self::$$r_method[$url]['controller']. "Controller",
                                'init'
                            ]);

                            call_user_func([
                                self::$$r_method[$url]['controller']. "Controller",
                                self::$$r_method[$url]['method']
                            ]);

                            die();
                        }
                        else
                            die("controller '" . self::$$r_method[$url]['controller'] ."' not found!");
                    }
                }
                // looking for the best match
                else
                {
                    $url = explode('/', $url);

                    foreach (self::$$r_method as $tmp => $action)
                    {
                        $tmp = explode('/', filter_var( rtrim($tmp , '/') , FILTER_SANITIZE_URL) );

                        if (count($url) === count($tmp))
                        {
                            // replacing the parts that begins with ':' and save it in an array $data
                            for ($i = 0; $i < count($url); $i++)
                            {
                                if ($i > 0)
                                {
                                    if (substr($tmp[$i],0,6) === "$(int)" )
                                    {
                                        if ( $url[$i] == "0" || intval($url[$i]) != "0")
                                        {
                                            $data[str_replace('$(int)','', $tmp[$i])] =  intval($url[$i]);

                                            $tmp[$i] = $url[$i];
                                        }
                                    }
                                    elseif (substr($tmp[$i],0,8) === "$(float)" )
                                    {
                                        if ( $url[$i] == "0" || floatval($url[$i]) != "0")
                                        {
                                            $data[str_replace('$(float)','', $tmp[$i])] =  floatval($url[$i]);

                                            $tmp[$i] = $url[$i];
                                        }
                                    }
                                    elseif (strpos($tmp[$i], '$') !== false)
                                    {
                                        $data[str_replace('$','', $tmp[$i])] =  $url[$i];

                                        $tmp[$i] = $url[$i];
                                    }
                                }
                            }
                            // checking if the url matches the rout
                            if (count(array_diff($tmp, $url)) === 0)
                            {
                                if (  $action['call_type'] == 'function'  )
                                {
                                    if (  is_callable( $action['function'])  )
                                    {
                                        $action['function']($data);

                                        die();
                                    }
                                }
                                else
                                {

                                    Loader::controller($action['controller']);


                                    call_user_func([
                                            $action['controller'] . "Controller",
                                            'init'
                                        ], $data);

                                    call_user_func([
                                            $action['controller'] . "Controller",
                                            $action['method']
                                        ], $data);

                                    die();
                                }
                            }
                        }
                    }
                }

            }
        }
        else
        {
            Loader::controller("home");

            call_user_func( ["homeController" , "index"] );

            die();
        }
        if (isset($err) && $err !== null)
            die($err);

        Loader::html("notfound");// or "home if you want it to redirect to home controller

        die();
    }


}

/* router v 0.1 */