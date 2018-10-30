<?php

use Luna\lib\Andromeda\src\AndromedaDriver;
use Luna\services\Storage\{
    Dir,
    File
};


class JSON_AD extends AndromedaDriver
{
    private $tables;
    private $records = [];

    # helpers functions

    /**
     * add database to databases list
     *
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
     * remove a db about from db list
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

    private function refresh_table($data = null , $tables = null)
    {
        if (empty($tables))
            $tables = is_array($this->query['tables']) ? $this->query['tables'] : [$this->query['tables']];

        elseif ( ! is($tables,'ary'))
            $tables = [$tables];

        if (is($tables, 'ary'))
        {
            foreach ($tables as $table => $key)
            {
                $d = (new File($this->config['db_location'] . $this->db['name'] . DS . $key . ".json" ));

                $d->put(json_encode($data), true);
            }
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
                    $this->records[$table] = $this->load_data($table . ".json") ;
                }
            }
            $r = [];
            foreach ($this->records as $table => $records )
            {
                foreach ($records as $key => $record)
                {
                    $tm = $this->trait_query($record);

                    if ($tm)
                    {
                        $r[$table][$key] = $tm;
                    }
                }
            }

            $this->records = $r;

            $t = [];
            foreach ($this->tables as $table => $val)
            {
                $t[$table] = [];
            }

            return (! empty($r)) ? $r : $t;
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
     * @return $this|mixed
     * @throws Error
     * @throws Exception
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

        if ( isset($_db['secure']) && $_db['secure'] && ( ($_db['user'] != $config['user']) || ($_db['pass'] != $config['pass']) ) )
        {
            error("<b>Error!</b> couldn't login database '" . $_db['name'] . "', wrong username or password");
        }
        else
        {
            $this->config = $_config;

            $this->db = $_db;

            $this->tables = $data['tables'];

            $this->connection = true;

            $this->pass = (isset($_db['pass']) and ! empty($_db['pass'])) ? $_db['pass'] : "";
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

            $this->pass = (isset($db['pass']) and ! empty($db['pass'])) ? $db['pass'] : "";

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

        $this->db = $this->tables = $this->records =null;
    }

    /**
     * @return mixed
     */
    public function getTables()
    {
        return array_keys($this->tables);
    }

    # requests functions


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

        $r = $this->fetchAll($style) ;

        return ( is($r,"ary") and count($r) > 0 and is(array_values($r)[0],"ary") and count(array_values($r)[0])> 0) ? array_values(array_values($r)[0])[0] : [];
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

        return $r;
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

        $r = $this->fetch();

        $r = (is($r, "ary"))? arrayToObject($r,$classname) : arrayToObject([],$classname);

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

        $r = $this->fetchAll();

        $r = is($r,"ary") ? $r : [];

        $tmp = [];

        foreach ($r as $table => $records)
        {
            foreach ($records as $key => $record)
            {
                if (is($record,"ary"))
                    $tmp[$table][$key] = arrayToObject($record,$classname);
                else
                    $tmp[$table][$key] = arrayToObject([$record],$classname);
            }

        }

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

        $t = array_values($this->query['tables'])[0];

        $this->clear_query();

        return ( isset( $r [$t] [$key] ) ) ? $r [$t] [$key] : null;
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
     * @throws Exception
     */
    public function exec()
    {
        $this->check_connection();

        if ($this->lock)
        {
            exception("error! this connection is locked, you can make selection operations only.");
        }

        if ($this->query['action'] == 'insert')
        {
            $tables = $this->trait_data();

            foreach ($tables as $table => $r)
            {
                $r = is($r,"ary") ? $r : [];

                if ( ! (isset($this->query['override']) and $this->query['override']) && isset($r[$this->query['tmp_name']]))

                    exception("<b>Warning!</b> a record with same key '" . $this->query['tmp_name'] . "' exist in tables ["  . arrayToString($this->query['tables'])  . "] with no override permission");

                $r[$this->query['tmp_name']] = $this->query['tmp_data'] ;

                $this->refresh_table($r, $table);

            }

            $this->clear_query();

            return true;
        }

        elseif($this->query['action'] == "delete")
        {
            $tables = $this->trait_data();


            foreach ($tables as $table => $records)
            {
                $records = is($records,"ary") ? $records : [];

                $k = [];

                foreach ($records as $key => $record)
                {
                    //for each item in the table

                    $admit = true;

                    foreach ($this->query['where'] as $item => $value)
                    {
                        if (! $admit)
                        {
                            break;
                        }

                        if (array_key_exists($item, $record))
                        {
                            $value[1] = (!is($value[1], 'function') and substr($value[1],0,7) == '$this->' and array_key_exists(str_replace('$this->','',$value[1]),$record)) ? $record[str_replace('$this->','',$value[1])] : $value[1];

                            switch ($value[0])
                            {
                                case "==":
                                case "equals":
                                    if ($record[$item] == $value[1])
                                    {
                                        $adm = false;

                                        $k[] = $key;

                                        break;
                                    }
                                    break;

                                case "!=":
                                case "not equals":
                                    if ($record[$item] != $value[1])
                                    {
                                        $adm = false;

                                        $k[] = $key;

                                        break;
                                    }
                                    break;

                                case ">":
                                case "bigger than":
                                    if ($record[$item] > $value[1])
                                    {
                                        $adm = false;

                                        $k[] = $key;

                                        break;
                                    }
                                    break;

                                case ">=":
                                case "bigger or equals":
                                    if ($record[$item] >= $value[1])
                                    {
                                        $adm = false;

                                        $k[] = $key;

                                        break;
                                    }
                                    break;

                                case "<":
                                case "smaller than":
                                    if ($record[$item] < $value[1])
                                    {
                                        $adm = false;

                                        $k[] = $key;

                                        break;
                                    }
                                    break;

                                case "<=":
                                case "smaller or equals":
                                    if ($record[$item] <= $value[1])
                                    {
                                        $adm = false;

                                        $k[] = $key;

                                        break;
                                    }
                                    break;

                                case "><":
                                case "btn":
                                case "between":
                                    if ( ($record[$item] >= $value[1]) ||  ($record[$item] < $value[2]) )
                                    {
                                        $adm = false;

                                        $k[] = $key;

                                        break;
                                    }
                                    break;

                                case "in":
                                    if ( in_array($record[$item], $value[1]))
                                    {
                                        $adm = false;

                                        $k[] = $key;

                                        break;
                                    }
                                    break;

                                case "!in":
                                case "not in":
                                    if ( ! in_array($record[$item], $value[1]))
                                    {
                                        $adm = false;

                                        $k[] = $key;

                                        break;
                                    }
                                    break;

                                case "matches":
                                case "verifies":
                                    if (is($value[1],"function"))
                                    {
                                        echo 1;

                                        $fun = $value[1];
                                        if ( $fun($record[$item], $record))
                                        {
                                            $adm = false;

                                            $k[] = $key;

                                            break;
                                        }
                                    }
                                    break;


                                case "does not match":
                                case "does not verify":
                                    if (is($value[1],"function"))
                                    {
                                        $fun = $value[1];
                                        if ( ! $fun($record[$item], $record))
                                        {
                                            $adm = false;

                                            $k[] = $key;

                                            break;
                                        }
                                    }
                                    break;

                            }
                        }

                    }// end conditions loop

                }// end records loop

                foreach ($records as $key => $item)
                {
                    if ( in_array($key, $k))
                    {
                        if (isset($records[$key]))
                        {
                            unset($records[$key]);
                        }
                    }
                }

                $this->refresh_table($records,$table);

                $this->clear_query();

                return true;
            }

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
     * @param array|null $record
     * @return array|null
     */
    private function trait_query(array $record = null)
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

                if (array_key_exists($item, $record))
                {
                    $value[1] = (!is($value[1], 'function') and substr($value[1],0,7) == '$this->' and array_key_exists(str_replace('$this->','',$value[1]),$record)) ? $record[str_replace('$this->','',$value[1])] : $value[1];

                    switch ($value[0])
                    {
                        case "==":
                        case "equals":
                            if ($record[$item] != $value[1])
                            {
                                $adm = false;

                                $k[$item] = $record[$item];

                                break;
                            }
                            break;

                        case "!=":
                        case "not equals":
                            if ($record[$item] == $value[1])
                            {
                                $adm = false;

                                $k[$item] = $record[$item];

                                break;
                            }
                            break;

                        case ">":
                        case "bigger than":
                            if ($record[$item] <= $value[1])
                            {
                                $adm = false;

                                $k[$item] = $record[$item];

                                break;
                            }
                            break;

                        case ">=":
                        case "bigger or equals":
                            if ($record[$item] < $value[1])
                            {
                                $adm = false;

                                $k[$item] = $record[$item];

                                break;
                            }
                            break;

                        case "<":
                        case "smaller than":
                            if ($record[$item] >= $value[1])
                            {
                                $adm = false;

                                $k[$item] = $record[$item];

                                break;
                            }
                            break;

                        case "<=":
                        case "smaller or equals":
                            if ($record[$item] > $value[1])
                            {
                                $adm = false;

                                $k[$item] = $record[$item];

                                break;
                            }
                            break;

                        case "><":
                        case "btn":
                        case "between":
                            if ( ! ( ($record[$item] >= $value[1]) ||  ($record[$item] < $value[2]) ) )
                            {
                                $adm = false;

                                $k[$item] = $record[$item];

                                break;
                            }
                            break;

                        case "in":
                            if (  ! in_array($record[$item], $value[1]))
                            {
                                $adm = false;

                                $k[$item] = $record[$item];

                                break;
                            }
                            break;

                        case "!in":
                        case "not in":
                            if ( in_array($record[$item], $value[1]))
                            {
                                $adm = false;

                                $k[$item] = $record[$item];

                                break;
                            }
                            break;

                        case "matches":
                        case "verifies":
                            if (is($value[1],"function"))
                            {
                                $fun = $value[1];
                                if ( ! $fun($record[$item], $record))
                                {
                                    $adm = false;

                                    $k[$item] = $record[$item];

                                    break;
                                }
                            }
                            break;


                        case "does not match":
                        case "does not verify":
                            if (is($value[1],"function"))
                            {
                                $fun = $value[1];
                                if (  $fun($record[$item], $record))
                                {
                                    $adm = false;

                                    $k[$item] = $record[$item];

                                    break;
                                }
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

                $array = keys($k, $record);
            }
        }

        return $adm ? $record : null;
    }
}