<?php

namespace Luna\services\Timer\command;


use Luna\services\Cli\Command;

class date extends Command
{
    public  function __call($function, $args)
    {
        echo " " . date("D M d Y") . NL;
    }

    public  function __invoke($arg = null)
    {
        echo " " . date("D M d Y") . NL;
    }

    public  function help(){}
}