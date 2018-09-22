<?php


namespace Luna\Services\Http;

use Luna\Services\Storage;

class Request
{
    /*---------------------------------------------------
    |       # Request
    |--------------------------------------------------
    */
    protected $headers = [];

    private static $data = [];

    protected $method;

    private static $instance;
    private static $init = false;

    /**
     * @param null $r_method
     * @return Request
     * @throws \Error
     */
    public static function instance($r_method = null)
    {
        if (! self::$init)
        {
            if (empty($r_method))
                error("Error! request method required, null passed.");

            self::$init = true;

            self::$instance = new self($r_method);

            return self::$instance;
        }
        else
            return self::$instance;
    }

    private function __construct($r_method)
    {
        $this->method = $r_method;

        switch ( strtolower($r_method) ){
            case 'get' :
                foreach ($_GET as $get => $value)
                    self::$data[$get] = $value;
                break;

            case 'post':
                foreach ($_POST as $post => $value)
                    self::$data[$post] = $value;
                break;
        }

        $this->headers = getallheaders();
    }

    public function __get($var)
    {
        if (array_key_exists($var,self::$data))
            return self::$data[$var];

        else
            return null;

    }

    public function __set($var, $val)
    {

    }

    public function valueOf($key, $rm = null)
    {
        if (isset($rm) and in_array(strtolower($rm),['get','post']))
        {
            if (strtolower($rm) == 'get' and isset($_GET[$key]))
                return $_GET[$key];
            elseif (strtolower($rm) == 'post' and isset($_POST[$key]))
                return $_POST[$key];

        }
        elseif ( !isset($rm) )
        {
            if ($this->method() == 'get' or $this->method() =='GET'and isset($_GET[$key]))
                return $_GET[$key];
            elseif ($this->method() == 'post' or $this->method() =='POST'and isset($_POST[$key]))
                return $_POST[$key];
            else
                return null;
        }
        else
        {
            return null;
        }
    }

    public function header($name)
    {
        return isset($this->headers[$name]) ? $this->headers[$name]: null;
    }

    public function method()
    {
        return $this->method;
    }

    /**
     * @description return the http request status
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function status()
    {
        return $_SERVER['REDIRECT_STATUS'];
    }

    /**
     * @description return the http host name
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function host()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * @description return the http connection type
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function HTTP_CONNECTION()
    {
        return $_SERVER['HTTP_CONNECTION'];
    }

    /**
     * @description return the http accepted data types
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function HTTP_ACCEPT()
    {
        return explode(",", $_SERVER['HTTP_ACCEPT']) ;
    }

    /**
     * @description return the http request language
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function language()
    {
        return explode(",",explode(";", $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0])[1] ;
    }

    /**
     * @description return the http request full language string
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function language_full()
    {
        return $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }

    /**
     * @description return the http request phpsessid cookie
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function PHPSESSID()
    {
        return explode("=",explode(";", $_SERVER['HTTP_COOKIE'])[1])[1];
    }

    /**
     * @description return the http request scheme
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function scheme()
    {
        return $_SERVER['REQUEST_SCHEME'];
    }

    /**
     * @description return the http redirect URL
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function redirect_url()
    {
        return $_SERVER['REDIRECT_URL'];
    }

    /**
     * @description return the http request query
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function query()
    {
        return $_SERVER['QUERY_STRING'];
    }

    /**
     * @description return the http request file
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function self()
    {
        return $_SERVER['PHP_SELF'];
    }

    /**
     * @description return the http request time
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function time()
    {
        return $_SERVER['REQUEST_TIME'];
    }

    /**
     * @description return the http request full time
     * @author Ali Aouf
     * @since 2
     * @return mixed
     */
    public function time_float()
    {
        return $_SERVER['REQUEST_TIME_FLOAT'];
    }

    public function get($key)
    {
        return (isset($_GET[$key])) ? $_GET[$key] : null ;
    }

    public function post($key)
    {
        return (isset($_POST[$key])) ? $_POST[$key] : null ;
    }

    public function file($key)
    {
        return (isset($_FILES[$key])) ? $_FILES[$key] : null ;
    }

    public function upload($file, $config = null)
    {
        return Storage::store( $this->file($file), $config);
    }

    public function server_ip()
    {
        if (!empty($_SERVER['SERVER_ADDR']) && self::validate_ip($_SERVER['SERVER_ADDR']))
            return $_SERVER['SERVER_ADDR'];

        // return unreliable ip since all else failed
        return $_SERVER['REMOTE_ADDR'];
    }

    public  function client_ip() {
        // check for shared internet/ISP IP
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && self::validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        // check for IPs passing through proxies
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // check if multiple ips exist in var
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
                $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($iplist as $ip) {
                    if (self::validate_ip($ip))
                        return $ip;
                }
            } else {
                if (self::validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
                    return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED']) && self::validate_ip($_SERVER['HTTP_X_FORWARDED']))
            return $_SERVER['HTTP_X_FORWARDED'];
        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && self::validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && self::validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
            return $_SERVER['HTTP_FORWARDED_FOR'];
        if (!empty($_SERVER['HTTP_FORWARDED']) && self::validate_ip($_SERVER['HTTP_FORWARDED']))
            return $_SERVER['HTTP_FORWARDED'];

        // return unreliable ip since all else failed
        return $_SERVER['REMOTE_ADDR'];
    }

    public function client_browser($key = null)
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];

        $bname = 'Unknown';

        $platform = 'Unknown';

        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Edge/i',$u_agent))
        {
            $bname = 'Microsoft Edge';
            $ub = "Edge";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }
        else
        {
            $bname = '';
            $ub = "";
        }

        // finally get the correct version number

        $known = array('Version', $ub, 'other');

        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

        if (!preg_match_all($pattern, $u_agent, $matches))
        {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);

        if ($i != 1)
        {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else
            {
                $version= $matches['version'][1];
            }
        }
        else
        {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        if(isset($key) and in_array($key, ["userAgent", "name", "version", "platform", "plattern"]))
        {
            switch ($key)
            {
                case "userAgent":
                    return $u_agent;
                    break;
                case "name":
                    return $bname;
                    break;
                case "version":
                    return $version;
                    break;
                case "platform":
                    return $platform;
                    break;
                case "pattern":
                    return $pattern;
                    break;
                default:
                    return null;
                    break;
            }
        }
        else
            return array(
                'userAgent' => $u_agent,
                'name'      => $bname,
                'version'   => $version,
                'platform'  => $platform,
                'pattern'    => $pattern
            );
    }
}
