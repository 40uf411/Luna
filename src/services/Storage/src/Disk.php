<?php

namespace Luna\Services\Storage\src;


abstract class Disk
{
    protected $config;

    protected static $validation;

    public function __construct($config)
    {
        $this->config = $config;

        $this->connect($config["db"]);

        unset($config["db"]);

        $this->load();
    }
    
    public function config($config): self
    {
        $this->config = $config;

        return $this;
    }

    public function val($val)
    {
        self::$validation = $val;

        return $this;
    }

    public function connect($db){}

    protected function testF($file)
    {
        $val = self::$validation;

        if ($val['validations']["min"] and ($file['size'] <  $val['min_size']) )
            return "small";

        if ($val['validations']["max"] and ($file['size'] >  $val['max_size']) )
            return "big";

        switch ($val['validations']["type"])
        {
            case 1:
                if( ! in_array($file['type'],$val['allowed_types']) )
                    return "not allowed";
                break;

            case -1:
                if( in_array($file['type'],$val['not_allowed_types']) )
                    return "not allowed";
                break;
        }
        if ($file['error'] != 0)
            return "error";

        return "ok";
    }

    public function load(){}

    public function exist($key){}

    public function url($key){}

    public function get($key){}

    public function remove($key){}

    public function upload($file, $config = null){}

    public function download($key){}

}