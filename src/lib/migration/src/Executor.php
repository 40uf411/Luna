<?php

namespace Luna\lib\migration\src;

class Executor
{
    private static $DEFAULT_FETCH = null;
    
    public static function setup(array $settings)
    {

        if (isset($settings['DEFAULT_FETCH']))

            self::$DEFAULT_FETCH = $settings['DEFAULT_FETCH'];
    }

    public static function execute($sql)
    {
        return SQLexecute::execute($sql);
    }
    
    public static function fetch_all($sql)
    {
        $query = self::execute($sql);

        return $query->fetchAll(self::$DEFAULT_FETCH);
    }

    public static function fetch($sql)
    {
        $query = self::execute($sql);

        return $query->fetch(self::$DEFAULT_FETCH);
    }

    public static function fetch_column($sql)
    {
        $query = self::execute($sql);

        return $query->fetchColumn(self::$DEFAULT_FETCH);
    }

    public static function fetch_object($sql, $class_name = null, $constrictor_attributes =null)
    {
        $query = self::execute($sql);

        return $query->fetchObject($class_name, $constrictor_attributes);
    }

    public static function build(array $table)
    {
        return SQLgenerator::table($table);
    }
    public static function drop($table)
    {
        return SQLgenerator::drop($table);
    }
    public static function insert(array $data)
    {
        return SQLgenerator::insert($data);
    }

    public static function update($row)
    {
        return SQLgenerator::update($row);
    }
    public static function delete($row)
    {
        return SQLgenerator::delete($row);
    }

}