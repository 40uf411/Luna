<?php

namespace Luna\Core;

use Luna\Andromeda\Andromeda;
use Luna\Helpers\Loader;

Loader::lib();

class Model
{
    private static $db_cnx = null;
    private static $db_type = "mysql";
    private static $table = "";
    private static $primaryKey = "id";
    private static $timestamps = false;

    private $attributes = [];
    private $hidden = [];

    private $data;

    private function db_connect($config = [])
    {
        $this->db_cnx = Andromeda::connect([], 'mysql');
    }

    public function __construct() 
    {
        self::$table = get_called_class();

        // connect to andromeda
        $this->db_connect();
    }

    public function __get($var)
    {
        if (! in_array($var, $this->hidden) and in_array($var, $this->attributes))
        {
            if (isset($this->data[$var]))
                return $this->data[$var];
            else
                return null;
        }
        return false;
    }

    public function __set($var, $value)
    {
        if (! in_array($var, $this->hidden) and in_array($var, $this->attributes))
            $this->data[$var] = $value;
    }

    public static final function all()
    {
        # code...
    }
    
    public static final function find($var)
    {
        # code...
    }

    public function save()
    {
        # code...
    }

    public function delete()
    {
        # code...
    }
}