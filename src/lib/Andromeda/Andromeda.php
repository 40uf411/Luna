<?php

namespace Luna\Andromeda;

use Luna\Helpers\Loader;
use Luna\lib\Andromeda\src\AndromedaDriver;

class Andromeda
{
    private static $default;
    private static $config;

    public static function config()
    {
        $config = Loader::config("lib" . DS . "Andromeda" . DS . "Andromeda");

        self::$default = $config['default'];

        self::$config = $config['config'];

        require_once "src" . DS . "AndromedaDriver.php";

        require_once "Model.php";
    }

    /**
     * @param $db
     * @param null $driver
     * @param null $config
     * @return AndromedaDriver
     */
    public static function connect($db, $driver = null, $config = null)
    {
        $driver = $driver ? $driver : self::$default ;

        $driver = strtoupper($driver);

        $config = $config ? $config : self::$config[$driver];

        $driver = $driver . "_AD" ;

        require_once "src" . DS . $driver . ".php";

        $driver = new $driver($config);

        return $driver->connect($db);
    }

    /**
     * @param $db
     * @param null $driver
     * @param null $config
     * @return AndromedaDriver
     */
    public static function create($db, $driver = null, $config = null)
    {
        $driver = $driver ? $driver : self::$default ;

        $driver = strtoupper($driver);

        $config = $config ? $config : self::$config[$driver];

        $driver = $driver . "_AD" ;

        require_once "src" . DS . $driver . ".php";

        $driver = new $driver($config);

        return $driver->create($db);
    }

}