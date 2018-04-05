<?php

namespace Luna\Providers;

use Luna\Providers\Log as Log;

class CookiesProvider
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
     * @param array $conf
     */
    public static function config(array $conf)
    {

        if ( key_exists('DEFAULT_PREFIX', $conf) )
            self::$cookies_config['DEFAULT_PREFIX'] = $conf['DEFAULT_PREFIX'];

        if ( key_exists('DEFAULT_EXPIRE_TIME',$conf) )
        {
            $time = $conf['DEFAULT_EXPIRE_TIME'];

            if ( is_string( $time ) )
            {
                $time = self::treat_time($time);
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
     * @param $time
     * @return float|int
     */
    private static function treat_time($time)
    {
        if ( is_string( $time ))
        {
            $char = substr( $time, -1 );

            $t =  intval(substr($time, 0, -1)) ;

            switch ($char){
                case 's': break;
                case 'm': $t *= 60; break;
                case 'h': $t *= 60*60; break;
                case 'd': $t *= 60*60*24; break;
                case 'w': $t *= 60*60*24*7; break;
                case 'M': $t *= 60*60*24*30; break;
                case 'y': $t *= 60*60*24*365; break;
                default : break;
            }
            return $t;
        }
        return $time;
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
                null + intval(($expire != null)? time() + self::treat_time($expire) : self::$cookies_config['DEFAULT_EXPIRE_TIME']),
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