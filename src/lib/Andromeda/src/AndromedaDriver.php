<?php

namespace Luna\lib\Andromeda\src;


abstract class AndromedaDriver
{
    protected $config;

    protected $query = [
        "action" => [],
        "select" => "*",
        "tables" => [] ,
        "where" => [],
    ];
    public function __construct($config)
    {
        $this->config = $config;
    }

    public abstract function connect($config);

    /**
     * @param $table
     * @param array|null $schema
     * @return mixed
     */
    public abstract function create_table($table,array $schema = null);

    /**
     * @param $select
     * @return $this
     */
    public abstract function select($select = "*");

    /**
     * @param $table
     * @return $this
     */
    public abstract function from($table);

    /**
     * @param $where
     * @param $condition
     * @return mixed
     */
    public abstract function where($where, $condition = null);

    /**
     * @param $where
     * @param null $condition
     * @return mixed
     */
    public abstract function with($where, $condition = null);

    /**
     * @param $key
     * @return $this
     */
    public abstract function orderBy($key);

    /**
     * @param $key
     * @return $this
     */
    public abstract function groupBy($key);

    public abstract function fetch($style = null);

    public abstract function fetchAll($style = null);

    public abstract function fetchObj($classname = null);

    public abstract function fetchObjs($classname = null);

    public abstract function count();

    /**
     * @param $record
     * @return $this
     */
    public abstract function insert($name,$record = null);

    /**
     * @param $table
     * @return $this
     */
    public abstract function inTo($table);

    /**
     * @return mixed
     */
    public abstract function delete();

    public abstract function drop($table);

    public abstract function drop_self();

    public abstract function close();

    public abstract function exec();
}