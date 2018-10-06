<?php

namespace Luna\services\Cli\commands;


use Luna\services\Cli\{
    Auth, command, Progress, Printer, Table
};
use Luna\services\Console;

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

        $s = NL . " " . $p->render(" welcome to Luna. ",["bg_white", "blue"]) . NL ;
        $s .= $this->getOptGuide();

        $s .= $p->render(NL . " Valid commands:",["blue"]);
        $t = new Table(["command", "description"]);
        $t->setChar("col", "");
        $t->setChar("line", "");

        foreach (Console::getCommands() as $command => $value)
        {
            $t->insert(["command" => $command, "description" => (isset($value['description']))? $value['description'] : ""]);
        }
        return $s . $t->render();
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

    /**
     * @param $function
     * @param $args
     * @throws \Error
     */
    public  function __call($function, $args)
    {
        //(new Auth())->login();
    }
}