<?php
/**
 * Created by PhpStorm.
 * User: ali25
 * Date: 10/2/2018
 * Time: 5:02 PM
 */

namespace Luna\services\Timer\command;


use Luna\services\Cli\Command;

class time extends Command
{
    public  function __call($function, $args)
    {
        echo " " . date("H : I : S");
    }

    public  function __invoke($arg = null)
    {
        echo " " . date("H : i : s");
    }

    public  function help(){}

}