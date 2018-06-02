<?php

namespace Luna\lib\migration;

use Luna\lib\migration\src\Executor;

require_once "src/Executor.php";
require_once "src/SQLgenerator.php";
require_once "src/SQLexecute.php";

class Migration
{
    public function __construct()
    {
    }

    public static function setup(array $settings)
    {
        Executor::setup($settings);
    }
    public static function execute($commond_file)
    {
        return Executor::execute($commond_file);
    }

    public static function build(array $table)
    {
        self::execute( Executor::build($table) );

        return SUCCESS;
    }

    public static function drop($table_name)
    {
        self::execute( Executor::drop($table_name) );
    }

    public static function insert(array $data)
    {
       self::execute( Executor::insert($data) );

        return SUCCESS;
    }

    public static function update(array $row)
    {
        self::execute( Executor::update($row) );

        return SUCCESS;
    }

    public static function delete(array $row)
    {
        self::execute( Executor::delete($row) );

        return SUCCESS;
    }

    public static function fetch($sql)
    {
        return Executor::fetch($sql);
    }

    public static function fetch_all($sql)
    {
        return Executor::fetch_all($sql);
    }

    public static function fetch_column($sql)
    {
        return Executor::fetch_column($sql);
    }

    public static function fetch_object($class_name, $constrictor_attributes)
    {
        return Executor::fetch_object($class_name, $constrictor_attributes);
    }
}