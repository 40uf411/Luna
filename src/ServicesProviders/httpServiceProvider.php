<?php

namespace Luna\ServiceProviders;

use Luna\Core\ServiceProvider;

class HTTP extends ServiceProvider
{

    public static function redirect($page = null) {
        header('Location: ' . URL . $page);
    }

    public static function get_request_method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @param $name
     * @return bool
     */
    public static function get($name)
    {
        return (self::in_get($name)) ? $_GET[$name] : false;
    }

    /**
     * @param $name
     * @return bool
     */
    public static function post($name)
    {
        return (self::in_post($name)) ? $_POST[$name] : false;
    }

    /**
     * @param $name
     * @return bool
     */
    public static function files($name)
    {
        return (self::in_files($name)) ? $_FILES[$name] : false;
    }

    /**
     * @param $name
     * @return bool
     */
    public static function in_post($name)
    {
        return (self::get_request_method() && isset($_POST[$name])) ;
    }

    /**
     * @param $name
     * @return bool
     */
    public static function in_get($name)
    {
        return (!self::get_request_method() && isset($_GET[$name])) ;
    }

    /**
     * @param $name
     * @return bool
     */
    public static function in_files($name)
    {
        return isset($_FILES[$name]) ;
    }

    /**
     *
     */
    public static function dump()
    {
        echo "<pre>[GET] ".print_r($_GET, true)."</pre>";

        echo "<pre>[POST] ".print_r($_POST, true)."</pre>";
    }

    /**
     *
     */
    public static function dump_post()
    {
        echo "<pre>[POST] ".print_r($_POST, true)."</pre>";
    }

    /**
     *
     */
    public static function dump_get()
    {
        echo "<pre>[GET] ".print_r($_GET, true)."</pre>";
    }

    /**
     *
     */
    public static function dump_files()
    {
        echo "<pre>[FILES] ".print_r($_FILES, true)."</pre>";
    }
    /**
     * @param int $statusCode
     */
    public static function send_response_code($statusCode = 200)
    {
        http_response_code($statusCode);
    }

    /**
     * @param array $data
     * @param int $statusCode
     */
    public static function render_json($data=[], $statusCode=200)
    {
        http_response_code($statusCode);

        header('Content-type: application/json');

        echo json_encode($data);

        exit();
    }

    /**
     * @param $url
     * @return bool|string
     */
    public static function load($url)
    {
        return file_get_contents($url);
    }

    /**
     * @param $json
     * @return mixed
     */
    public static function to_array($json)
    {
        return json_decode($json,true);
    }

    /**
     * @param $json
     * @return mixed
     */
    public static function to_obj($json)
    {
        return json_decode($json);
    }

    /**
     * @return mixed
     */
    public static function get_server_name()
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * @return mixed
     */
    public static function get_server_detail()
    {
        return $_SERVER['SERVER_SIGNATURE'];
    }

    /**
     * @return mixed
     */
    public static function get_server_ip_address()
    {
        if (!empty($_SERVER['SERVER_ADDR']) && self::validate_ip($_SERVER['SERVER_ADDR']))
            return $_SERVER['SERVER_ADDR'];

        // return unreliable ip since all else failed
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @return mixed
     */
    public static function get_client_detail()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * @return mixed
     */
    public static function get_client_ip_address() {
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

    /**
     * Ensures an ip address is both a valid IP and does not fall within
     * a private network range.
     */
    public static function validate_ip($ip) {
        if (strtolower($ip) === 'unknown')
            return false;

        // generate ipv4 network address
        $ip = ip2long($ip);

        // if the ip is set and not equivalent to 255.255.255.255
        if ($ip !== false && $ip !== -1) {
            // make sure to get unsigned long representation of ip
            // due to discrepancies between 32 and 64 bit OSes and
            // signed numbers (ints default to signed in PHP)
            $ip = sprintf('%u', $ip);
            // do private network range checking
            if ($ip >= 0 && $ip <= 50331647) return false;
            if ($ip >= 167772160 && $ip <= 184549375) return false;
            if ($ip >= 2130706432 && $ip <= 2147483647) return false;
            if ($ip >= 2851995648 && $ip <= 2852061183) return false;
            if ($ip >= 2886729728 && $ip <= 2887778303) return false;
            if ($ip >= 3221225984 && $ip <= 3221226239) return false;
            if ($ip >= 3232235520 && $ip <= 3232301055) return false;
            if ($ip >= 4294967040) return false;
        }
        return true;
    }

    /**
     * @return mixed
     */
    public static function get_port()
    {
        return $_SERVER['SERVER_PORT'];
    }

    /**
     * @return mixed
     */
    public static function get_root_dir()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    /**
     * @return mixed
     */
    public static function get_request_code()
    {
        return $_SERVER['REDIRECT_STATUS'];
    }

    /**
     * @return mixed
     */
    public static function get_request_scheme()
    {
        return $_SERVER['REQUEST_SCHEME'];
    }

    /**
     * @param bool $float
     * @return mixed
     */
    public static function get_request_time()
    {
        return time() - $_SERVER['REQUEST_TIME_FLOAT'] ;
    }

    /**
     * @param bool $full
     * @return mixed
     */
    public static function get_script($full = false)
    {
        return ($full) ? $_SERVER['SCRIPT_FILENAME'] : $_SERVER['SCRIPT_NAME'] ;
    }

    /**
     * @return array
     */
    public static function get_browser()
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

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }
}