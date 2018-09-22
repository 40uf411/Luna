<?php

namespace Luna\lib\Factory\command;


use Luna\lib\Factory\Builder;
use Luna\services\Cli\Command;

class Factory extends Command
{
    public function setup()
    {
        parent::setup();

        $this->setOption("detect","force building the object if it already exist.",'d','detect');
        $this->setOption("force","force building the object if it already exist.",'f','force');
    }

    public function __invoke($args = null)
    {
        echo $this->help();
    }
    public  function __call($function, $args)
    {
        $args = $args[0];
        if ($function == "make")
        {
            if (isset($args['what']))
            {
                Builder::build($args['what'],$args);
            }
        }
        else
        {
            echo "missing arguments";
        }
    }

    public  function help()
    {
        echo "welcome to the factory";
        echo $this->getOptGuide();
    }
}