<?php

namespace Luna\lib\Whirlpool;


class Memorise
{
    private static $memo_configed = false;

    public static function key($key)
    {
        if (!self::$memo_configed)
        {
            Memory::config([
               "db_name" => "Whirlpool",
               "db_user" => "root",
               "db_pass" => "toor"
            ]);
            self::$memo_configed = true;
        }
        return new Memory($key);
    }
}