<?php

namespace Luna\lib\migration\src;



class table
{
    public $name;
    public $columns;
    public $engine;
    public $charset;

    public function __construct($name,array $columns, $engine = 'InnoDB', $charset = 'UTF8')
    {
        $this->name = $name;

        $this->columns = $columns;

        $this->engine = $engine;

        $this->charset = $charset;
    }

    public function __destruct()
    {
    }
}