<?php

namespace Luna\lib\Andromeda\src;


abstract class AndromedaDriver
{
    protected $connection = false;

    protected $lock = false;

    protected $db;

    protected $pass = "";

    protected $config;

    protected $query = [
        "action" => [],
        "select" => "*",
        "tables" => [] ,
        "where" => [],
    ];


    /**
     * AndromedaDriver constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param $config
     * @return mixed
     */
    public abstract function connect($config);

    /**
     * @param $table
     * @param array|null $schema
     * @return mixed
     */
    public abstract function create_table($table,array $schema = null);

    /**
     * @param string $select
     * @return $this
     * @throws \Exception
     */
    public function select($select = "*")
    {
        $this->check_connection();


        $this->query["action"] = "select" ;
        $this->query["select"] = $select ;

        return $this;
    }

    /**
     * @param array ...$tables
     * @return $this
     * @throws \Exception
     */
    public function from(...$tables)
    {
        $this->check_connection();

        $this->query["tables"] = array_merge($this->query["tables"], $tables) ;

        return $this;
    }

    /**
     * @param $where
     * @param array ...$condition
     * @return $this
     * @throws \Exception
     */
    public function where($where, ...$condition)
    {
        $this->check_connection();

        $this->query["where"] = array_merge($this->query["where"], [$where => $condition]) ;

        return $this;
    }

    /**
     * @param $where
     * @param null $condition
     * @return $this
     * @throws \Exception
     */
    public function with($where, $condition = null)
    {
        $this->check_connection();


        if ($condition == null and is($where, "array"))
            $this->query["where"] = array_merge($this->query["where"], $where) ;
        elseif ($condition != null)
            $this->query["where"] = array_merge($this->query["where"], [$where => $condition]) ;

        return $this;
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function orderBy($key)
    {
        $this->check_connection();

        $this->query["order"][] = $key ;

        return $this;
    }

    /**
     * @param $key
     * @return $this
     * @throws \Exception
     */
    public function groupBy($key)
    {
        $this->check_connection();


        $this->query["group"][] = $key ;

        return $this;
    }

    /**
     * @param null $style
     * @return mixed
     */
    public abstract function fetch($style = null);

    /**
     * @param null $style
     * @return mixed
     */
    public abstract function fetchAll($style = null);

    /**
     * @param null $classname
     * @return mixed
     */
    public abstract function fetchObj($classname = null);

    /**
     * @param null $classname
     * @return mixed
     */
    public abstract function fetchObjs($classname = null);

    /**
     * @return mixed
     */
    public abstract function count();

    /**
     * @param $name
     * @param null $record
     * @return $this
     * @throws \Exception
     */
    public function insert($name,$record = null)
    {
        $this->check_connection();


        $this->query['action'] = "insert";

        if ( ! empty($record))
        {
            $this->query['tmp_name'] = $name;

            $this->query['tmp_data'] = $record;
        }
        else
        {
            $this->query['tmp_name'] = uniqid();

            $this->query['tmp_data'] = $name;
        }

        return $this;
    }

    /**
     * @param array ...$tables
     * @return $this
     * @throws \Exception
     */
    public function inTo(...$tables)
    {
        $this->check_connection();

        $this->query["tables"] = array_merge($this->query["tables"], $tables) ;

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function delete()
    {
        $this->check_connection();


        $this->query['action'] = "delete";

        return $this;
    }

    /**
     * @param $table
     * @return mixed
     */
    public abstract function drop($table);

    /**
     * @return mixed
     */
    public abstract function drop_self();

    /**
     * @return mixed
     */
    public abstract function close();

    /**
     * @return mixed
     */
    public abstract function exec();

    /**
     * @return $this
     */
    public function lock()
    {
        $this->lock = true;

        return $this;
    }

    /**
     * @param $password
     * @return $this
     */
    public function unlock($password)
    {
        if ($this->pass === $password)
            $this->lock = false;

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected  function check_connection()
    {
        /**
        if (! $this->connection)
        {
            dump($this->connection);
            exception("error! you need to connection before you make a request");
        }*/
    }
}