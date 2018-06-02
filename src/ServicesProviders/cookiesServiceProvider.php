<?php

namespace Luna\ServiceProvider;

use Luna\Core\ServiceProvider;
use Luna\Helpers\Loader;
use Luna\ServiceProvider\Logger as Log;
use Luna\helpers\Converter;

class Cookies extends ServiceProvider
{
    private static $cookies_config = [
        'DEFAULT_PREFIX' => null,
        'DEFAULT_EXPIRE_TIME' => null,
        'DEFAULT_PATH' => null,
        'DEFAULT_DOMAIN' => null,
        'DEFAULT_SECURE' => false,
        'DEFAULT_HTTP_ONLY' => false,
    ];

    /**
     *
     */
    public static function config()
    {
        $conf = Loader::config("providers" . DS ."cookies");

        if ( key_exists('DEFAULT_PREFIX', $conf) )
            self::$cookies_config['DEFAULT_PREFIX'] = $conf['DEFAULT_PREFIX'];

        if ( key_exists('DEFAULT_EXPIRE_TIME',$conf) )
        {
            $time = $conf['DEFAULT_EXPIRE_TIME'];

            if ( is_string( $time ) )
            {
                $time = Converter::treat_time($time);
            }
            self::$cookies_config['DEFAULT_EXPIRE_TIME'] = time() + $time;
        }
        else
            self::$cookies_config['DEFAULT_EXPIRE_TIME'] = time() + (86400 * 15);

        if ( key_exists('DEFAULT_PATH', $conf) )
            self::$cookies_config['DEFAULT_PATH'] = $conf['DEFAULT_PATH'];

        if ( key_exists('DEFAULT_DOMAIN', $conf) )
            self::$cookies_config['DEFAULT_DOMAIN'] = $conf['DEFAULT_DOMAIN'];

        if ( key_exists('DEFAULT_SECURE', $conf) && is_bool($conf['DEFAULT_SECURE'] ) )
            self::$cookies_config['DEFAULT_SECURE'] = $conf['DEFAULT_SECURE'];

        if ( key_exists('DEFAULT_HTTP_ONLY', $conf) && is_bool($conf['DEFAULT_HTTP_ONLY'] ) )
            self::$cookies_config['DEFAULT_HTTP_ONLY'] = $conf['DEFAULT_HTTP_ONLY'];

        Log::save('cookies', 'Cookies configurations have bees changed', 'Warning');
    }


    /**
     * @param $name
     * @param null $value
     * @param null $expire
     * @param null $path
     * @param null $domain
     * @param null $secure
     * @param null $httponly
     */
    public static function add($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        //echo $name."|".$value."|".$expire."|".$path."|".$domain."|".$secure."|".$httponly."| done!";

        if (! key_exists($name, $_COOKIE))
        {
            setcookie(
                self::$cookies_config['DEFAULT_PREFIX'] . $name,
                null . $value,
                null + intval(($expire != null)? time() + Converter::treat_time($expire) : self::$cookies_config['DEFAULT_EXPIRE_TIME']),
                ($path != null)? $path : self::$cookies_config['DEFAULT_PATH'],
                ($domain != null)? $domain : self::$cookies_config['DEFAULT_DOMAIN'],
                ($secure != null)? $secure : self::$cookies_config['DEFAULT_SECURE'],
                ($httponly != null)? $httponly : self::$cookies_config['DEFAULT_HTTP_ONLY']
            );
            $_COOKIE[self::$cookies_config['DEFAULT_PREFIX'] . $name] = $value;
            Log::save('cookies', "added a new cookie [ $name | $value ]", 'Success');
        }
        else
        {
            Log::save('cookies', "Failed to add cookie [ $name | $value ] cookie already exist", 'Fail');
        }

    }

    /**
     * @param $name
     * @param $value
     */
    public static function edit($name, $value)
    {
        if (key_exists( self::$cookies_config['DEFAULT_PREFIX'].$name , $_COOKIE))
        {
            setcookie(self::$cookies_config['DEFAULT_PREFIX'].$name, $value);

            $_COOKIE[ self::$cookies_config['DEFAULT_PREFIX'].$name ] = $value;

            Log::save('cookies', "changed the value of cookie [ $name ] cookie", 'Success');
        }
        else
        {
            Log::save('cookies', "Failed to change the value of cookie [ $name ] cookie doesn't exist", 'Fail');
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public static function get($name)
    {
        if (key_exists( self::$cookies_config['DEFAULT_PREFIX'].$name , $_COOKIE))
        {
            Log::save('cookies', "Getting the value of cookie [ $name ]", 'Alert');

            return $_COOKIE[ self::$cookies_config['DEFAULT_PREFIX'].$name ] ;
        }
    }

    /**
     * @param $name
     */
    public static function delete($name, $path = null)
    {
        if (key_exists( self::$cookies_config['DEFAULT_PREFIX'].$name , $_COOKIE))
        {
            setcookie( self::$cookies_config['DEFAULT_PREFIX'].$name , "", time() - 3600, $path);

            unset( $_COOKIE[self::$cookies_config['DEFAULT_PREFIX'].$name ] );

            Log::save('cookies', "deleted cookie [ $name ] ", 'Success');
        }
        else
        {
            Log::save('cookies', "Failed to delete cookie [ $name ] cookie doesn't exist", 'Fail');
        }
    }
    public static function dump()
    {
        echo "<pre>".print_r($_COOKIE, true)."</pre>";
    }

    public static function unset_all()
    {
        foreach ($_COOKIE as $c => $v)
        {

            $c = substr($c,strlen(self::$cookies_config['DEFAULT_PREFIX']));

            self::delete($c);

            unset($_COOKIE[$c]);
        }
    }
}