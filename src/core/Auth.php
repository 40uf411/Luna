<?php

namespace Luna\Core;

use Luna\ServiceProvider\Sessions;

abstract class Auth
{
    /*
     |--------------------------------------
     |
     |      #       #
     |
     |--------------------------------------
     |
     |
     |
     |
     */

    public static function log_level($level)
    {
        return $level <= APP_LOG_LEVEL;
    }


    /*
     |--------------------------------------
     |
     |      #       #
     |
     |--------------------------------------
     |
     |
     |
     |
     */

    public static function key_generator($use_session = true ,$allow_refresh = false)
    {
        $key = rand(100000000,1000000000) . date("His") . APP_KEY;

        if ($use_session)

            Sessions::add("auth_key", $key, $allow_refresh);

        return $key;
    }

    public static function key_validator($key)
    {
        return $key === Sessions::get("auth_key");
    }

    public static function root_permission($password)
    {
        return $password === APP_ROOT_PASSWD;
    }
}