<?php

use Luna\lib\Factory\Template;

class Controller extends Template
{
    public function make($what, $data = null)
    {
        dump($data);
    }
}