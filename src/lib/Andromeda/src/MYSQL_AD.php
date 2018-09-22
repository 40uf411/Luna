<?php

use Luna\lib\Andromeda\src\AndromedaDriver;

class MYSQL_AD extends AndromedaDriver
{
    private $connection = false;

    private $db;

    /**
     * @param null $config
     * @return $this
     */
    public function connect($config = null)
    {
        $this->config = (! empty($config) and is($config,"ary") and hasKeys($config, ['host', 'name','user','pass'])) ? $config : $this->config;

        try
        {
            $this->db = new PDO("mysql:host=" . $this->config['host'] . "; dbname=" . $this->config['name'], $this->config['user'], $this->config['pass'], $this->config['options']);

            if (isset($this->config['charset']))

            //$this->setChar($this->config['charset']);

            $this->connection = true;
        }
        catch (PDOException $e)
        {
            ob_clean();
            ?>
            <div style="background: #fff;padding: 20px; font-size: 3em; position: absolute; left: 50%; transform: translate(-50%,0); top: 5%; font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,Arial,sans-serif; ">
                <b>Oops! </b> Failed to connect to databese </br>
                <div style="font-size:.5em;margin-top:30px">'
                    <?php echo $e->getMessage() ?> ';
                </div>
            </div>
            <?php
            die();
        }

        return $this;
    }
/*
    private function setChar($charest){

        $sql = 'set names '.$charest;

        $this->query($sql);

    }


*/

    /**
     * @param $sql
     * @return mixed
     */
    public function query($sql)
    {
        $this->stm = $this->db->query($sql);

        return $this;
    }

    /**
     * @param $sql
     * @param array|null $data
     * @return $this
     * @throws Exception
     */
    public function execute($sql, array $data = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $this->stm = $this->db->prepare($sql);

        $this->stm->execute($data);

        return $this;
    }

    /**
     * @param $table
     * @param array|null $schema
     * @return mixed
     * @throws Exception
     */
    public function create_table($table,array $schema = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $str = "CREATE TABLE `" . $this->config['name'] . "`.`" . $table ."` ( ";

        $co = 1;

        foreach ($schema as $key => $value)
        {
            $str =  $str . "`$key` $value ";

            if ($co < count( $schema ) )
            {
                $str = $str . ", ";
                $co++;
            }
        }

        $str = $str . ") ENGINE = InnoDB;";

        return $this->query($str);
    }

    /**
     * @param string $select
     * @return $this
     * @throws Exception
     */
    public function select($select = "*")
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $this->query['select'] = $select;

        return $this;
    }

    /**
     * @param $table
     * @return $this
     * @throws Exception
     */
    public function from($table)
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $table = (is_array($table)) ? $table : [$table];

        $this->query["tables"] = array_merge($this->query["tables"], $table) ;

        return $this;
    }

    /**
     * @param array $where
     * @return $this
     * @throws Exception
     */
    public function where(array $where)
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $this->query["where"] = array_merge($this->query["where"], $where) ;

        return $this;
    }

    /**
     * @param array $with
     * @return $this
     * @throws Exception
     */
    public function with(array $with)
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $this->query["where"] = array_merge($this->query["where"], $with);

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
            exception("error! you need to connect before you make a request");
        }

        $this->query["order"] = $key ;

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
            exception("error! you need to connect before you make a request");
        }

        $this->query["group"] = $key ;

        return $this;
    }

    private function treat_query()
    {
        $select = (is($this->query['select'],"ary"))? $this->query['select'] : [$this->query['select']];
        $group = (isset($this->query['group']))? $this->query['group'] : null;
        $order = (isset($this->query['order']))? $this->query['order'] : null;

        $str = "SELECT " . substr(arrayToString($select,', $v'),1) . " FROM " .
            substr(arrayToString($this->query['tables'],', `$v`'),1);

        if ( ! empty($this->query['where']))

            $str = $str . " WHERE " . substr(arrayToString($this->query['where'],'and $k $v '),4);

        if (! empty($group))

            $str = $str . " GROUP BY " . $group;

        if (! empty($order))

            $str = $str . " ORDER BY " . $order;

        echo $str;
        return $this->query($str);
    }

    /**
     * @param null $style
     * @return mixed
     * @throws Exception
     */
    public function fetch($style = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        if (isset($this->stm))
        {

            $s =  $this->stm->fetch($style);

            $this->clear_query();

            return $s;
        }

        else
        {
            $s  =  $this->treat_query()->fetch($style = null);

            $this->clear_query();

            return $s;
        }
    }

    /**
     * @param null $style
     * @return mixed
     * @throws Exception
     */
    public function fetchAll($style = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        if (isset($this->stm))
        {
            $s = $this->stm->fetchAll($style);

            $this->clear_query();

            return $s;
        }

        else
        {
            $s  = $this->treat_query()->fetchAll($style = null);

            $this->clear_query();

            return $s;
        }
    }

    /**
     * @param string $classname
     * @return mixed
     * @throws Exception
     */
    public function fetchObj($classname = "stdClass")
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        if (isset($this->stm))
        {
            $s = $this->stm->fetchObject($classname);

            $this->clear_query();

            return $s;
        }

        else
        {
            $s = $this->treat_query()->fetchObject($classname);

            $this->clear_query();

            return $s;
        }
    }

    /**
     * @param string $classname
     * @return mixed
     * @throws Exception
     */
    public function fetchObjs($classname = "stdClass")
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        if (isset($this->stm))
        {
            $s =  $this->stm->fetchAll(PDO::FETCH_CLASS, $classname);

            $this->clear_query();

            return $s;
        }

        else
        {
            $s = $this->treat_query()->fetchAll(PDO::FETCH_CLASS, $classname);

            $this->clear_query();

            return $s;
        }

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
     * @param $name
     * @param null $record
     * @return $this
     * @throws Exception
     */
    public function insert($name,$record = null)
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $this->query['action'] = "insert";

        if (isset($record))
        {
            $this->query['tmp_name'] = $name;

            $name = $record;
        }

        $this->query['tmp_data'] = $name;

        return $this;
    }

    /**
     * @param $table
     * @return $this
     * @throws Exception
     */
    public function inTo($table)
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $table = (is_array($table)) ? $table : [$table];

        $this->query["tables"] = array_merge($this->query["tables"], $table) ;

        return $this;
    }

    /**
     * @return $this|mixed
     * @throws Exception
     */
    public function delete()
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $this->query['action'] = "delete";

        return $this;
    }

    public function update($table)
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $this->query['action'] = "update";

        $this->query['tables'] = is($table, "ary")? $table : [$table];

        $this->query['set'] = [];

        return $this;
    }

    public function set($attributes)
    {
        $this->query['set'] = $attributes;

        return $this;
    }

    /**
     * @param $table
     * @throws Exception
     */
    public function drop($table)
    {

        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $this->query("DROP TABLE `$table`");

        $this->clear_query();
    }

    public function drop_self(){}

    /**
     * @throws Exception
     */
    public function close()
    {

        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $this->connection = false;

        $this->db = null;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function exec()
    {
        if (! $this->connection)
        {
            exception("error! you need to connect before you make a request");
        }

        $str = "";
        switch ($this->query['action'])
        {
            case "insert":
                $str = "INSERT INTO ";
                $str = $str . substr(arrayToString($this->query['tables'],', `$v`'),1) . " (" .
                    substr(arrayToString($this->query['tmp_data'],', `$k`'),1) . ") VALUES (" .
                    substr(arrayToString($this->query['tmp_data'],', "$v"'),1) . ");" ;

                break;

            case "delete":
                $str = 'DELETE FROM ';
                $str = $str . substr(arrayToString($this->query['tables'],', `$v`'),1)  ;
                if(!empty($this->query['where']))
                {
                    $str = $str . " WHERE ";
                    $str = $str . substr(arrayToString($this->query['where'],'and $k $v '),4);
                }
                break;

            case "update":
                $str = "UPDATE ";
                $str = $str . substr(arrayToString($this->query['tables'],', `$v`'),1)  ;
                $str = $str . " SET ";
                $str = $str . substr(arrayToString($this->query['set'],', `$k` = "$v"'),1);
                if(!empty($this->query['where']))
                {
                    $str = $str . " WHERE ";
                    $str = $str . substr(arrayToString($this->query['where'],'and $k $v '),4);
                }

                break;
        }
        $this->clear_query();
        //return $str;
        $this->query($str);
    }

    private function clear_query()
    {
        $this->query = [
            "action" => [],
            "select" => "*",
            "tables" => [] ,
            "where" => [],
        ];

        if (isset($this->stm))
            unset($this->stm);
    }
}