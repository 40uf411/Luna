<?php

namespace Luna\App\Model;

use Luna\Core\Model;
use Luna\lib\migration\Migration;

class User extends Model
{
    protected $table = "users";

    public $name;

    public function __construct($id)
    {
        parent::__construct();

        $result =  Migration::fetch("SELECT name FROM users WHERE id = $id");

        $this->name = $result['name'];
    }
}