<?php

namespace Luna\lib\migration\src;

class attribute
{
    public $name;
    public $type;
    public $length;
    public $nullable;
    public $attribute;
    public $comment;
    public $values;

    public function __construct($name, $value)
    {
        $this->name  = $name;
        $this->values = $value;
    }

}