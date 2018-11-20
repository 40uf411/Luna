<?php

namespace Luna\Services;

use Luna\Core\Service;
use Luna\Services\Storage\src\Disk;


class Storage extends Service
{
    private static $default;

    private static $url;

    private static $cd;

    private static $config;

    private static $disks;

    public static function init($info = null)
    {
        parent::init($info);

        require_once "src" . DS . "File.php";
        require_once "src" . DS . "Dir.php";
        require_once "src" . DS . "Disk.php";
    }
    
    public static function config($info = null)
    {
        parent::config($info);

        self::$default = $info["default"];

        self::$config = $info["config"];

        self::$disks = $info["disks"];

        self::$url = $info["base_url"];

        foreach ($info["base_url"] as $url => $value)
        {
            self::$cd[$value[0]] = $url;
        }

        foreach (self::$url  as $disk => $url)
        {
            Router::any('/files/$s/' . $url[1], function($data){
                if (array_key_exists($data['s'], Storage::$cd))
                {
                    $s = $data['s'];
                    unset($data['s']);
                    Storage::disk(Storage::$cd[$s])->download($data);
                }
            });
        }
    }

    /**
     * @param null $name
     * @param null $config
     * @return Disk
     */
    public static function disk($name = null, array $config = null)
    {
        $name =  $name ? $name : self::$default;

        $config =  $config ? $config : self::$disks[$name];

        $config = array_merge($config, ["url" => self::$url[$name][0]]);

        $name = $name . "_storageDisk";

       require_once "drivers" . DS . $name . ".php";

       return (new $name($config))->val(self::$config);
    }

    public static function connect()
    {

    }
    
    public static function exist(){}

    public static function url(){}

    public static function store($file, $config = null)
    {
        return self::disk()->upload($file, $config);
    }

    public static function save(){}

    public static function download(){}
}