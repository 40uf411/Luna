<?php

namespace Luna\App\Model;

use Luna\Core\Model;

class User extends Model
{
    protected $table = "users";

    public $name;

    public function __construct($id)
    {
        parent::__construct();

        $result = $this->db->getData(false, "name", $this->table,"where id = " . $id);

        $this->name = $result['name'];
    }
}