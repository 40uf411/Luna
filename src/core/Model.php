<?php

namespace Luna\Core;

use Luna\Andromeda\Andromeda;
use Luna\Helpers\Loader;

Loader::lib();

class Model
{
    protected static $db_need = false;
    protected static $db_cnx = null;

    public static $db_type = "json";
    public static $table = "";
    public static $primaryKey = "id";
    public static $timestamps = false;

    protected static $attributes = [];
    protected static $default = [];
    protected static $hidden = [];

    private $data = [];

    public static function isConnected()
    {
        return ( ! self::$db_need  or (self::$db_need and ! empty(self::$db_cnx) )) ? true : false ;
    }
    
    public static function config(array $config , $table = null ){

        $class = get_called_class();

        $class::db_connect($config);

        $class::$table = (empty($table))? get_called_class() : $table ;
    } 

    private static function db_connect(array $config)
    {
        $class = get_called_class();

        $db_name = $config['name'];
        if (array_key_exists("pass", $config) and array_key_exists("pass", $config))
        {
            $db_pass = $config['pass'];
            $db_user = $config['user'];
            $class::$db_cnx = Andromeda::connect(["name" => $db_name ,'pass'  => $db_pass , 'user' => $db_user], $class::$db_type);
        }
        else
        {
            $class::$db_cnx = Andromeda::connect(["name" => $db_name], $class::$db_type);
        }
    }

    public function __construct()
    {
        if ( ! self::isConnected())
            error("you must configure the module first");
    }

    public function get($var)
    {
        $class = get_called_class();

        if ( ! self::isConnected())
            error("you must configure the module first");

        if (! in_array($var, $class::$hidden) and in_array($var, $class::$attributes))
        {
            if (isset($this->data[$var]))
                return $this->data[$var];
            else
                return null;
        }
        return false;
    }

    public function __get($var)
    {
        $class = get_called_class();

        if ( ! self::isConnected())
            error("you must configure the module first");

        if (! in_array($var, $class::$hidden) and in_array($var, $class::$attributes))
        {
            if (isset($this->data[$var]))
                return $this->data[$var];
            else
                return null;
        }
        return null;
    }

    public function set($var, $value)
    {
        $class = get_called_class();

        if ( ! self::isConnected())
            error("you must configure the module first");

        if (! in_array($var, $class::$hidden) and in_array($var, $class::$attributes))
            $this->data[$var] = $value;
    }

    public function __set($var, $value)
    {
        $class = get_called_class();

        if ( ! self::isConnected())
            error("you must configure the module first");

        if (! in_array($var, $class::$hidden) and in_array($var, $class::$attributes))
            $this->data[$var] = $value;
    }

    private final static function parse(array $obj)
    {
        $class = get_called_class();
        $o = new $class();

        foreach ($class::$attributes as $attribute)
        {
            if (array_key_exists($attribute,$obj))
                $o->data[$attribute] = $obj[$attribute];

            elseif (array_key_exists($attribute,$class::$default))
                $o->data[$attribute] = $class::$default[$attribute];
        }

        return $o;
    }

    /**
     * @return mixed
     * @throws \Error
     */
    public static final function all()
    {
        $class = get_called_class();

        if ( empty($class::$db_cnx) )
            error("you must configure the module first");

        $r = $class :: $db_cnx -> select()->from($class::$table)->fetchAll()[$class::$table];

        foreach ($r as $row)
            $tmp[$row[$class::$primaryKey]] = self::parse($row);

        return (isset($tmp))? $tmp : [] ;
    }

    /**
     * @param $what
     * @return mixed
     * @throws \Error
     */
    public static final function find($what)
    {
        $class = get_called_class();

        if ( empty($class::$db_cnx) )
            error("you must configure the module first");

        $r = $class :: $db_cnx -> select()->from($class::$table)->where($class::$primaryKey, "==",$what)->fetch();

        return self::parse($r);
    }

    /**
     * @return mixed
     * @throws \Error
     */
    public function save()
    {
        if ( empty(self::$db_cnx) )
            error("you must configure the module first");

        return self :: $db_cnx -> insert(objectToArray($this))->inTo(self::$table)->exec() ;
    }

    /**
     * @return mixed
     * @throws \Error
     */
    public function delete()
    {
        if ( empty(self::$db_cnx) )
            error("you must configure the module first");

        $p = self::$primaryKey;

        return self::$db_cnx -> delete()->from(self::$table)->where($p , "==" , $this->$$p )->exec(); ;
    }
}