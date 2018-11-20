<?php
namespace Luna\services;


use Luna\Core\Service;

class Session extends Service
{

    private $name;

    private $value;

    private static $config;

    public static function init($info = null)
    {
        parent::init($info);
    }

    public static function config($info = null)
    {
        parent::config($info);

        self::$config = $info;

        if (!is_cli())
        {
            if ( isset(self::$config['DEFAULT_SESSION_NAME']) )
            {
                session_name ( self::$config['DEFAULT_SESSION_NAME'] ) ;
            }

            session_save_path(SESSIONS_PATH );

            if ( session_status() === PHP_SESSION_NONE )
            {
                session_start () ;
            }

        }

    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function exist($key)
    {
        return ( isset($_SESSION[$key]) and ! empty($_SESSION[$key]) );
    }

    public static function get($name)
    {
        return $_SESSION[$name];
    }

    public static function reset ()
    {
        session_reset () ;
    }

    public static function close ()
    {
        session_abort () ;
    }

    public static function destroy($sessions = null)
    {
        if (is_array($sessions))
        {
            foreach ($sessions as $session)
            {
                if ($sessions instanceof self)
                {
                    unset($_SESSION[$session->getName()]);

                    unset($session);
                }
                elseif (is_string($session) and isset($_SESSION[$session]))
                {
                    unset($_SESSION[$sessions]);
                }
            }
        }
        elseif ($sessions instanceof self)
        {
            unset($_SESSION[$sessions->getName()]);

            unset($sessions);
        }
        elseif (is_string($sessions) and isset($_SESSION[$sessions]))
        {
            unset($_SESSION[$sessions]);
        }
        elseif ( empty($session))
        {
            unset($_SESSION);

            session_destroy();
        }
    }

    public function __toString() :? string
    {
        return $this->value;
    }

    public function __construct($name, $value = '')
    {
        $_SESSION[$name] = $value;

        $this->name = $name;

        $this->value = $value;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function load(string $value): void
    {
        $this->value = $_SESSION[ $value ];
    }

    public function value(string $value): void
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

}