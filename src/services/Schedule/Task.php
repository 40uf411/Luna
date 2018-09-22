<?php

namespace Luna\services\Schedule;


class Task
{
    private $name;
    private $data;

    public function __construct( $function )
    {
        $this->data = $function;
    }

    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    public function execute()
    {
        $fun  =$this->data;

        $fun();
    }
}