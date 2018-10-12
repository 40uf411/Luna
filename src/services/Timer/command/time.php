<?php

namespace Luna\services\Timer\command;


use Luna\services\Cli\Command;

class time extends Command
{
    public  function __call($function, $args)
    {
        echo " " . date("H : I : s") . NL;
    }

    public  function __invoke($arg = null)
    {
        echo " " . date("H : i : s") . NL;
    }
    public  function help(){}
}