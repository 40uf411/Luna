<?php

use Luna\lib\Andromeda\src\AndromedaDriver;
use Luna\services\Storage\{
    Dir,
    File
};


class JSON_AD extends AndromedaDriver
{
    private $connection = false;
    private $lock = false;
    private $db;
    private $tables;
    private $recordes = [];

    # helpers functions

    /**
     * @param $db
     */
    private function record_db($db)
    {
        $f = new File($this->config['db_location'] . "databases.json");

        $data =  json_decode($f->content(),true);

        $data[$db['name']]= $db;

        $f->put( json_encode($data) , true);
    }

    /**
     *
     */
    private function unrecord_db()
    {
        $db = $this->db;

        $f = new File($this->config['db_location'] . "databases.json");

        $data =  json_decode($f->content(),true);

        unset($data[$db['name']]);

        $f->put( json_encode($data) , true);
    }

    /**
     * @return mixed
     */
    private function get_record_db()
    {
        $f = new File($this->config['db_location'] . "databases.json");

        return json_decode($f->content(),true);
    }

    /**
     * @param null $db
     * @param null $config
     * @return $this
     */
    private function reset_db($db = null, $config = null)
    {
        $f = new File($this->config['db_location']. $this->db['name']. DS . "db.json");

        $db = $db ? $db: $this->db;

        $config = $config ? $config: $this->config;
        $f->put( json_encode([
            "global_config" => $config,
            "db_config" => $db,
            "tables" => $this->tables
        ]) , true);

        return $this;
    }

    private function refresh_table($data = null )
    {
        $tables = is_array($this->query['tables']) ? $this->query['tables'] : [$this->query['tables']];

        foreach ($tables as $table => $key)
        {
            $d = (new File($this->config['db_location'] . $this->db['name'] . DS . $key . ".json" ));

            $d->put(json_encode($data), true);
        }
    }

    /**
     * @param $file
     * @return mixed
     */
    private function load_data($file)
    {
        $d = (new File($this->config['db_location'] . $this->db['name'] . DS . $file ))->content();

        return json_decode($d, true);
    }

    /**
     * @return array
     * @throws Exception
     */
    private function trait_data()
    {
        if (!empty($this->query['tables']))
        {
            foreach ($this->query['tables'] as $table)
            {
                if (array_key_exists($table,$this->tables))
                {
                    $this->recordes = array_merge($this->recordes, $this->load_data($table . ".json")) ;
                }
            }
            $t = [];

            foreach ($this->recordes as $key => $record )
            {
                $tm = $this->trait_query($record);
                if ($tm)
                {
                    $t[$key] = $tm;
                }

            }
            return $t;
        }
        else
        {
            exception("no table selected");
        }
    }


    # management functions

    /**
     * JSON_AD constructor.
     * @param $config
     */
    public function __construct($config)
    {
        return $this->config = $config;
    }

    /**
     * @param $config
     * @return $this
     * @throws Error
     */
    public function connect($config)
    {
        if ($this->lock)
        {
            exception("error! this connection is locked, you can make selection operations only.");
        }

        $f = new File($this->config ["db_location"] . $config['name'] . DS . "db.json");

        $data = json_decode( $f->content(), true);

        $_config = $data['global_config'];

        $_db = $data['db_config'];

        if ( $_db['secure'] && ( ($_db['user'] != $config['user']) || ($_db['pass'] != $config['pass']) ) )
        {
            error("<b>Error!</b> couldn't login database '" . $_db['name'] . "', wrong username or password");
        }
        else
        {
            $this->config = $_config;

            $this->db = $_db;

            $this->tables = $data['tables'];

            $this->connection = true;
        }
        return $this;
    }

    /**
     * @param $db
     * @return $this
     * @throws Exception
     */
    public function create($db)
    {
        if ($this->lock)
        {
            exception("error! this connection is locked, you can make selection operations only.");
        }

        if (! array_key_exists($db['name'], $this->get_record_db()))
        {
            $this->db = $db;

            $this->tables = [];

            $d = new Dir($this->config['db_location'], $this->db['name']);

            $d->host('db.json',json_encode([
                "global_config" => $this->config,
                "db_config" => $db,
                "tables" => []
            ]));

            $this->record_db($db);

            $this->connection = true;

            return $this;
        }
        else
        {
            throw new \Exception('DB already exist!');
        }
    }

    /**
     * @param $table
     * @param array|null $schema
     * @return $this
     * @throws Exception
     */
    public  function create_table($table, array $schema = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }
        if ($this->lock)
        {
            exception("error! this connection is locked, you can make selection operations only.");
        }

        $sc = $schema ? true : false ;

        if (! array_key_exists($table, $this->tables) && $table != "db")
        {
            (new File($this->config['db_location'] . $this->db['name'] . DS . $table . ".json"))->put('{}');

            $this->tables[$table] = $sc;

            if ($sc)
                (new File($this->config['db_location'] . $this->db['name'] . DS . $table . "_schema.json"))->put( json_encode($schema) );

            $this->reset_db();
        }
        else
        {
            exception("warning! table already exists");
        }
        return $this;
    }

    /**
     * @param $table
     * @return $this
     * @throws Exception
     */
    public function drop($table)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }
        if ($this->lock)
        {
            exception("error! this connection is locked, you can make selection operations only.");
        }

        if (array_key_exists($table, $this->tables))
        {
            (new File($this->config['db_location'] . $this->db['name'] . DS . $table . ".json"))->delete();

            if($this->tables[$table])

                (new File($this->config['db_location'] . $this->db['name'] . DS . $table . "_schema.json"))->delete();

            unset($this->tables[$table]);

            $this->reset_db();
        }
        else
        {
            echo "no";
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function drop_self()
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }
        if ($this->lock)
        {
            exception("error! this connection is locked, you can make selection operations only.");
        }

        (new Dir($this->config['db_location'], $this->db['name']))->delete();

        $this->unrecord_db();
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function clean()
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }
        if ($this->lock)
        {
            exception("error! this connection is locked, you can make selection operations only.");
        }

        (new Dir($this->config['db_location'], $this->db['name']))->clean();

        $this->reset_db();

        return $this;
    }

    /**
     * @throws Exception
     */
    public  function close()
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }
        if ($this->lock)
        {
            exception("error! this connection is locked, you can make selection operations only.");
        }

        $this->connection = false;

        $this->db = $this->tables = $this->recordes =null;
    }

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
        if ($this->db['pass'] === $password)
            $this->lock = false;

        return $this;
    }

    # requests functions

    /**
     * @param $select
     * @return $this
     * @throws Exception
     */
    public function select($select = "*")
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        $this->query["action"] = "select" ;
        $this->query["select"] = $select ;

        return $this;
    }

    /**
     * @param $table
     * @return $this
     * @throws Exception
     */
    public  function from($table)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        $table = (is_array($table)) ? $table : [$table];

        $this->query["tables"] = array_merge($this->query["tables"], $table) ;

        return $this;
    }


    /**
     * @param $where
     * @param $condition
     * @return $this|mixed
     * @throws Exception
     */
    public function where($where, $condition = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        if ($condition == null and is($where, "array"))
            $this->query["where"] = array_merge($this->query["where"], $where) ;
        elseif ($condition != null)
            $this->query["where"] = array_merge($this->query["where"], [$where => $condition]) ;

        return $this;
    }

    /**
     * @param $where
     * @param null $condition
     * @return $this|mixed
     * @throws Exception
     */
    public function with($where, $condition = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        if ($condition == null and is($where, "array"))
            $this->query["where"] = array_merge($this->query["where"], $where) ;
        elseif ($condition != null)
            $this->query["where"] = array_merge($this->query["where"], [$where => $condition]) ;

        return $this;
    }

    /**
     * @param $key
     * @return $this
     * @throws Exception
     */
    public function orderBy($key)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }
        $this->query["order"][] = $key ;

        return $this;
    }

    /**
     * @param $key
     * @return $this
     * @throws Exception
     */
    public function groupBy($key)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        $this->query["group"][] = $key ;

        return $this;
    }

    /**
     *
     */
    public function override()
    {
        $this->query['override'] = true;

        return $this;
    }

    # getting data

    /**
     * @throws Exception
     */
    public function fetch($style = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        $r = $this->trait_data() ;

        $r = $r ? $r : [];

        $this->clear_query();

        return ( count($r) > 0 ) ? array_values($r)[0] : [];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function fetchAll($style = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        $r =  $this->trait_data();

        $this->clear_query();

        return $r ? $r : [];
    }

    /**
     * @param null $classname
     * @return mixed
     * @throws Exception
     */
    public function fetchObj($classname = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        $classname = $classname ? $classname : "stdClass";

        $r = $this->trait_data();

        $r = $r ? $r : [];

        $r =  ( count($r) > 0 ) ? array_values($r)[0] : [];

        $r = arrayToObject($r,$classname);

        $this->clear_query();

        return $r;
    }

    /**
     * @param null $classname
     * @return array
     * @throws Exception
     */
    public function fetchObjs($classname = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        $classname = $classname ? $classname : "stdClass";

        $r = $this->trait_data();

        $r = $r ? $r : [];

        $tmp = [];

        foreach ($r as $item)
        {
            $tmp[] = arrayToObject($item,$classname);
        }

        $this->clear_query();

        return $tmp;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        return count($this->fetchAll());
    }

    /**
     * @param $key
     * @return array
     * @throws Exception
     */
    public function get($key)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        $r = $this->trait_data() ;

        $this->clear_query();

        return (isset($r[$key])) ? $r[$key] : null;
    }

    /**
     * @param $key
     * @return mixed|null
     * @throws Exception
     */
    public function exist($key)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        return (! empty( $this->get($key))) ? true : false;
    }

    /**
     * @param $key
     * @throws Exception
     */
    public function remove($key)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }
        if ($this->lock)
        {
            exception("error! this connection is locked, you can make selection operations only.");
        }

        $r = $this->trait_data() ;

        if (isset($r[$key]))
            unset($r[$key]);

        $this->refresh_table($r);

        $this->clear_query();
    }

    # data operations

    /**
     * @param $record
     * @return $this
     * @throws Exception
     */
    public function insert($name, $record = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        $this->query['action'] = "insert";

        if (isset($record))
        {
            $this->query['tmp_name'] = $name;

            $name = $record;
        }
        else
            $this->query['tmp_name'] = null;
        $this->query['tmp_data'] = $name;

        return $this;
    }

    /**
     * @param $table
     * @return $this
     * @throws Exception
     */
    public  function inTo($table)
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        $table = (is_array($table)) ? $table : [$table];

        $this->query["tables"] = array_merge($this->query["tables"], $table) ;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function delete()
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }

        $this->query['action'] = "delete";

        return $this;
    }

    /**
     * @throws Exception
     */
    public function exec()
    {
        if (! $this->connection)
        {
            exception("error! you need to connection before you make a request");
        }
        if ($this->lock)
        {
            exception("error! this connection is locked, you can make selection operations only.");
        }

        if ($this->query['action'] == 'insert')
        {
            $r = $this->trait_data();

            $r = $r ? $r : [];

            if ( ! (isset($this->query['override']) and $this->query['override']) && isset($r[$this->query['tmp_name']]))

                exception("<b>Warning!</b> a record with same key '" . $this->query['tmp_name'] . "' exist in tables ["  . arrayToString($this->query['tables'])  . "] with no override permission");
                
            if (isset($this->query['tmp_name']))
                $r[$this->query['tmp_name']] = $this->query['tmp_data'] ;

            else
                $r[] = $this->query['tmp_data'] ;

            $this->refresh_table($r);

            $this->clear_query();

            return true;
        }

        elseif($this->query['action'] == "delete")
        {
            $r = $this->trait_data();

            $r = $r ? $r : [];

            $k = [];

            foreach ($r as $key => $array)
            {
                //for each item in the table

                $adm = true;

                foreach ($this->query['where'] as $item => $value)
                {
                    if (! $adm)
                    {
                        break;
                    }

                    if (array_key_exists($item, $array))
                    {
                        switch ($value[0])
                        {
                            case "equals":
                                if ($array[$item] == $value[1])
                                {
                                    $adm = false;

                                    $k[] = $key;

                                    break;
                                }
                                break;

                            case "not equals":
                                if ($array[$item] != $value[1])
                                {
                                    $adm = false;

                                    $k[] = $key;

                                    break;
                                }
                                break;

                            case "bigger than":
                                if ($array[$item] > $value[1])
                                {
                                    $adm = false;

                                    $k[] = $key;

                                    break;
                                }
                                break;

                            case "bigger or equals":
                                if ($array[$item] >= $value[1])
                                {
                                    $adm = false;

                                    $k[] = $key;

                                    break;
                                }
                                break;

                            case "smaller than":
                                if ($array[$item] < $value[1])
                                {
                                    $adm = false;

                                    $k[] = $key;

                                    break;
                                }
                                break;

                            case "smaller or equals":
                                if ($array[$item] <= $value[1])
                                {
                                    $adm = false;

                                    $k[] = $key;

                                    break;
                                }
                                break;

                            case "between":
                                if ( ($array[$item] >= $value[1]) ||  ($array[$item] < $value[2]) )
                                {
                                    $adm = false;

                                    $k[] = $key;

                                    break;
                                }
                                break;

                            case "in":
                                if ( in_array($array[$item], $value[1]))
                                {
                                    $adm = false;

                                    $k[] = $key;

                                    break;
                                }
                                break;

                            case "not in":
                                if ( ! in_array($array[$item], $value[1]))
                                {
                                    $adm = false;

                                    $k[] = $key;

                                    break;
                                }
                                break;

                        }
                    }

                }// end conditions loop

            }// end records loop

            $r = $this->trait_data();

            foreach ($r as $key => $item)
            {
                if ( in_array($key, $k))
                {
                    if (isset($r[$key]))
                    {
                        unset($r[$key]);
                    }
                }
            }

            $this->refresh_table($r);

            $this->clear_query();

            return true;

        } // end delete segment
    }

    /**
     *
     */
    private function clear_query()
    {
        $this->query = [
            "action" => [],
            "select" => "*",
            "tables" => [] ,
            "where" => [],
        ];
    }

    /**
     * @param array|null $array
     * @return array
     */
    private function trait_query(array $array = null)
    {
        $adm = true;

        if ($this->query['action'] == "select")
        {
            foreach ($this->query['where'] as $item => $value)
            {
                if (! $adm)
                {
                    break;
                }

                if (array_key_exists($item, $array))
                {
                    switch ($value[0])
                    {
                        case "equals":
                            if ($array[$item] != $value[1])
                            {
                                $adm = false;

                                break;
                            }
                            break;

                        case "not equals":
                            if ($array[$item] == $value[1])
                            {
                                $adm = false;

                                break;
                            }
                            break;

                        case "bigger than":
                            if ($array[$item] <= $value[1])
                            {
                                $adm = false;

                                break;
                            }
                            break;

                        case "bigger or equals":
                            if ($array[$item] < $value[1])
                            {
                                $adm = false;

                                break;
                            }
                            break;

                        case "smaller than":
                            if ($array[$item] >= $value[1])
                            {
                                $adm = false;

                                break;
                            }
                            break;

                        case "smaller or equals":
                            if ($array[$item] > $value[1])
                            {
                                $adm = false;

                                break;
                            }
                            break;

                        case "between":
                            if ( ($array[$item] < $value[1]) ||  ($array[$item] >= $value[2]) )
                            {
                                $adm = false;

                                break;
                            }
                            break;

                        case "in":
                            if ( ! in_array($array[$item], $value[1]))
                            {
                                $adm = false;

                                break;
                            }
                            break;

                        case "not in":
                            if ( in_array($array[$item], $value[1]))
                            {
                                $adm = false;

                                break;
                            }
                            break;
                    }
                }
                else
                {
                    $adm = false;

                    break;
                }
            }

            if ($this->query['select'] != '*' && ! empty($this->query['select']))
            {
                $k = is_array($this->query['select']) ? $this->query['select'] : [ $this->query['select'] ];

                $array = setKeys($k, $array);
            }
        }

        return $adm ? $array : null;
    }
}