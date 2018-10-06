<?php

namespace Luna\services\Timer\command;


use Luna\services\Cli\Command;

class date extends Command
{
    public  function __call($function, $args)
    {
        echo " " . date("Y - M - D | H : I : s");
    }

    public  function __invoke($arg = null)
    {
        echo " " . date("Y - M - D | H : I : s");
    }

    public  function help(){}
}