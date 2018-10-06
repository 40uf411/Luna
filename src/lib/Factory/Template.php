<?php


namespace Luna\lib\Factory;


abstract class Template
{
    public abstract function make($data = null);
}