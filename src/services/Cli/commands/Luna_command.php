<?php

namespace Luna\services\Cli\commands;


use Luna\services\Cli\Command;
use Luna\services\Cli\Printer;

class Luna_command extends Command
{
    private const version = 2.01;
    public function setup()
    {
        parent::setup();

        $this->setOption("version", "get the luna version","v","version");
        $this->setOption("factory", "launch a factory order","f","factory");
    }

    /**
     * @return \Luna\services\Cli\Color|string
     * @throws \Error
     */
    public  function help()
    {
        $p = new Printer();

        $s = NL . $p->render("welcome to Luna.",["bg_white", "blue"], true) . NL . NL;
        $s .= $this->getOptGuide();

        return $s;
    }

    public  function __invoke($args = null)
    {
        if (empty($this->set_options) || isset($this->set_options['h']))
        {
            echo $this->help();
        }
        elseif (isset($this->set_options['v']))
        {
            echo "v" . floatval(self::version);
        }
        else
        {
            echo "opt:";
            dump($this->set_options);
            echo "args:";
            dump($args);
        }
    }
    public  function __call($function, $args)
    {
        echo "opt:";
        dump($this->set_options);

        echo "function " . $function . NL;
        echo "args:";
        dump($args);
    }
}