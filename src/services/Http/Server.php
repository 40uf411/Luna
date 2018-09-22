<?php

namespace Luna\services\Http;


class Server
{
    public static function name()
    {
        return $_SERVER['SERVER_NAME'];
    }

    public static function addr()
    {
        return $_SERVER['SERVER_NAME'];
    }

    public static function port()
    {
        return $_SERVER['SERVER_PORT'];
    }

    public static function admin()
    {
        return $_SERVER['SERVER_ADMIN'];

    }

    public static function protocol()
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }
}