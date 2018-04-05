<?php

namespace Luna\core;

use Luna\Providers\DatabaseProvider;

abstract class Model
{
    protected $db; //database connection object

    protected $table; //table name

    protected $fields = array();  //fields list

    public function __construct(){

        $this->db = new databaseProvider();

        /*
                $this->table = $GLOBALS['config']['prefix'] . $table;

                $this->getFields();
            */
    }

}