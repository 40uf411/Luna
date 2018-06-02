<?php

namespace Luna\core;

use Luna\ServiceProvider\Databases;

abstract class Model
{
    protected $db; //database connection object

    protected $table; //table name

    protected $fields = array();  //fields list

    public function __construct(){

        $this->db = new Databases();

        /*
                $this->table = $GLOBALS['config']['prefix'] . $table;

                $this->getFields();
            */
    }

}