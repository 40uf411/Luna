<?php

namespace Luna\lib\Factory\command;


use Luna\lib\Factory\Builder;

use Luna\services\Cli\{
    Auth, command, Progress, Printer, Table
};

class Factory extends Command
{
    public function setup()
    {
        parent::setup();
        $this->setOption("force","force building the object if it already exist.",'f','force');
    }

    public function __invoke($args = null)
    {
        echo $this->help();
    }

    /**
     * @param $function
     * @param $args
     * @throws \Error
     */
    public  function __call($function, $args)
    {
        $args = $args[0];

        (new Auth())->login();

        if ($function == "make")
        {
            $this->requiredArgs(['what','name'],$args);

            Builder::build($args['what'],$args);
        }
        else
        {
            echo "missing arguments";
        }
    }

    /**
     * @throws \Error
     */
    public  function help()
    {
        $p = new Printer();
        echo NL . " " .  $p->render(" welcome to the factory ",["bg_white", "blue", "bold"]) . " " . NL;
        echo $this->getOptGuide();

        echo NL . " " . $p->render( "Functions:",["blue", "bold"]);
        $t = new Table(["name", "description"]);
        $t->setChar("col", "");
        $t->setChar("line", "");

        $t->insert(["name" => "make", "description" => "built an object."]);

        echo $t->render();

        echo NL . " " . $p->render( "Products:",["blue", "bold"]);
        $t = new Table(["name", "requirements", "optionals", "folder"]);
        $t->setChar("col", "");
        $t->setChar("line", "");

        $t->insert(["name" => "Controllers", "requirements" => "name", "folder" => CONTROLLERS_PATH]);
        $t->insert(["name" => "Commands", "requirements" => "name", "folder" => APP_PATH  . "commands" . DS]);

        echo $t->render();


    }
}