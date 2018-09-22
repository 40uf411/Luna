<?php


namespace Luna\lib\Factory;


abstract class Template
{
    public abstract function make($what, $data = null);
}