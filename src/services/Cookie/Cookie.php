<?php
namespace Luna\services;

use Luna\Core\Service;
use Luna\Helpers\Loader;

class Cookie extends Service
{
    private static $loaded = false;
    private static $config;
    public static $cookies = [];

    private $name;
    private $value;
    private $expire_time;
    private $path;
    private $domain;
    private $secure;
    private $http_only;
    private $flushed = false;


    public static function init($info = null){}

    public static function config($info = null)
    {
        parent::config();

        self::$config = $info;

        //var_dump($info);
    }

    public static function exist($name)
    {
        return isset($_COOKIE[$name]);
    }

    public static function load($name)
    {
        if ( isset($_COOKIE[$name]) )
            return self::new($name)->value($_COOKIE[$name]);
    }

    public static function restore($force_load = false)
    {
        if ($force_load or ! self::$loaded)
        {
            foreach ($_COOKIE as $c => $v)
            {
                self::new($c)->value($v);
            }
            self::$loaded = true;
        }
    }

    public static function new($name)
    {
        return new self($name);
    }

    public static function valueOf($name)
    {
        return  isset($_COOKIE[$name])? $_COOKIE[$name] : false ;
    }

    public static function get($name)
    {
        foreach (self::$cookies as $c)
        {
            if ($c->getName() == $name)
            {
                return $c;
            }
        }
    }

    public static function flushAll( $cookies = null)
    {
        if (empty($cookies))
        {
            foreach (self::$cookies as $cookie )
            {
                $cookie->flush();
            }
        }
        elseif ( is_array($cookies))
        {
            foreach ($cookies as $cookie )
            {
                if ($cookie instanceof self )
                    $cookie->flush();
            }
        }
        else
        {
            if ($cookies instanceof self )
                $cookies->flush();
        }
        return true;
    }

    public static function count()
    {
        return self::$cookies;
    }

    public static function destroy($cookie)
    {
        if (is_array($cookie))
        {
            foreach ($cookie as $item)
            {
                if ($item instanceof self)
                {
                    unset($_COOKIE[$item->getName()]);

                    setcookie($item->getName(),'', time() - 1050, $item->getPath());

                    unset($item);
                }
            }
        }
        elseif ($cookie instanceof self)
        {
            unset($_COOKIE[$cookie->getName()]);

            setcookie($cookie->getName(),'', time() - 1550, $cookie->getPath());

            unset($cookie);
        }
    }


    public function __toString()
    {
        return $this->value;
    }

    public function __construct($name, $value = null, $expire_time = null, $path = null, $domain = null, $secure = null, $http_only = null )
    {
        $this->name = self::$config['DEFAULT_PREFIX'] . $name;

        $this->value = empty( ! $value) ? $value : '';

        $this->expire_time = empty( ! $expire_time) ? $expire_time : self::$config['DEFAULT_EXPIRE_TIME'];

        $this->path = empty( ! $path) ? $path : self::$config['DEFAULT_PATH'];

        $this->domain = empty( ! $domain) ? $domain : self::$config['DEFAULT_DOMAIN'];

        $this->secure = empty( ! $secure) ? $secure : self::$config['DEFAULT_SECURE'];

        $this->http_only = empty( ! $http_only) ? $http_only : self::$config['DEFAULT_HTTP_ONLY'];

        self::$cookies[] = $this;

        return $this;
    }

    public function name(string $name): void
    {
        $this->name = $name;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function value($value)
    {
        $this->value = $value ;

        return $this;
    }
    public function getValue(): ?string
    {
        return $this->value;
    }

    public function expire_time($value)
    {
        $this->expire_time = $value ;

        return $this;
    }
    public function getExpireTime()
    {
        return $this->expire_time;
    }

    public function path($value)
    {
        $this->path = $value ;

        return $this;
    }
    public function getPath()
    {
        return $this->path;
    }

    public function domain($value)
    {
        $this->domain = $value ;

        return $this;
    }
    public function getDomain()
    {
        return $this->domain;
    }

    public function secure($value)
    {
        $this->secure = $value ;

        return $this;
    }
    public function isSecure()
    {
        return $this->secure;
    }

    public function http_only($value)
    {
        $this->http_only = $value ;

        return $this;
    }
    public function isHttpOnly()
    {
        return $this->http_only;
    }


    public function flush_once()
    {
        if (! $this->flushed)

            setcookie($this->name, $this->value, $this->expire_time, $this->path, $this->domain, $this->secure, $this->http_only);

        $this->flushed = true;
    }
    public function flush()
    {
        if (! $this->flushed)

            setcookie($this->name, $this->value, $this->expire_time, $this->path, $this->domain, $this->secure, $this->http_only);
    }


}
