<?php

namespace Luna\services;

use Luna\Core\Service;
use Luna\Helpers\Loader;
use Luna\services\Cli\Color;
use Luna\services\Cli\Command;
use Luna\services\Cli\commands\Luna_command;
use Luna\services\Cli\Printer;
use Luna\services\Cli\Scanner;
use Luna\services\storage\File;

class Console extends Service
{

    private static $config;
    private static $commands;

    public static function init($info = null)
    {
        require_once "Color.php";
        require_once "Printer.php";
        require_once "Scanner.php";
        require_once "Command.php";
        require_once "Progress.php";
        require_once __DIR__ . DS ."Table.php";
        require_once "Auth.php";
    }

    public static function config($info = null)
    {
        $c = self::$config = Loader::config("services" . DS . "console");

        self::$config = $c['configs'];

        self::$commands = $c['commands'];
    }


    /**
     * lunch the command treatment
     *
     * @param $arg
     * @throws \Error
     * @throws \Exception
     */
    public static function launch($arg)
    {
        $p = new Printer();
        $s = new Scanner();

        if (!is_cli() || $arg[0] !== 'cli')
        {
            $p->print("Error! This has to be run from the command line",["bg_red", "yellow"]);
            return;
        }

        unset($arg[0]);

        $t = self::treat($arg);

        $options = $t["options"];
        $command = $t['command'];
        $parameters = $t['parameters'];

        if (empty($command))
        {
            $c = self::$commands[self::$config['default_command']];
            require_once self::$commands[self::$config['default_command']]['location'];
            $c = new self::$commands[self::$config['default_command']]['namespace'];
            
            $c->setup();
            $c->renderOpt($options);
            $c();
        }
        else
        {
            if (isset(self::$commands[ $command['class'] ]) and File::exist(self::$commands[ $command['class'] ]['location']))
            {

                require_once self::$commands[ $command['class'] ]['location'];

                $c = self::$commands[ $command['class'] ]['namespace'];

                $c = new $c;

                if ($c instanceof Command)
                {
                    $c->setup();
                    $c->renderOpt($options);

                    if (!empty($command['function']))
                    {
                        $f = $command['function'];
                        $c->$f($parameters);
                    }
                    else
                        $c($parameters);

                }
                echo NL;
            }
            else
            {
                $p->print("Unknown command " . $command['class'] . "." . NL, "bold");
            }

        }
    }

    /**
     * treat an array of element and extract data from it
     *
     * @param $args
     * @return array read options, command and arguments
     */
    private static function treat($args)
    {
        $options = [];
        $command = [];
        $parameters = [];

        $mode = 0;

        foreach ($args as $arg)
        {
            switch ($mode)
            {
                case 0:
                    // getting long options
                    if (substr($arg,0,2) === "--")
                    {
                        $arg = explode(":",substr($arg,2));
                        $options[ $arg[0] ] = isset($arg[1])? $arg[1] : true;
                    }
                    // getting short options
                    elseif (substr($arg,0,1) === "-")
                    {
                        $arg = substr($arg,1);
                        if (strlen($arg) > 1 )
                        {
                            //option with value
                            if (str_split($arg)[1] == ":")
                            {
                                $v = substr($arg,2);
                                $options[str_split($arg)[0]] = (isset($v))? substr($arg,2) : "";
                            }
                            // group of options
                            else
                            {
                                $arg = str_split($arg);
                                foreach ($arg as $item)
                                {
                                    if(preg_match("/[a-z]|[A-Z]/",$item))
                                    {
                                        $options[$item] = true;
                                    }
                                }
                            }
                        }
                        elseif (strlen($arg) == 1)
                        {
                            $options[$arg] = true;
                        }
                    }
                    // getting function name
                    else
                    {
                        $arg = explode("::", $arg);
                        $command["class"] = $arg[0];
                        if (isset($arg[1]))
                            $command["function"] = $arg[1];

                        $mode = 1;
                    }
                    break;

                case 1:

                    $arg = explode(":",$arg);
                    $parameters[ $arg[0] ] = isset($arg[1])? $arg[1] : true;
                    break;
            }
        }

        return [
            "options" => $options,
            "command" => $command,
            "parameters" => $parameters
        ];
    }

    public static function getCommands()
    {
        return self::$commands;
    }

    // non needed function
    /*
     * Get options from the command line or web request
     *
     * @param string $options
     * @param array $longopts
     * @return mixed
     */
    /*
    public static function getoptreq ($options, $longopts)
    {
        if (PHP_SAPI === 'Cli' || empty($_SERVER['REMOTE_ADDR']))  // command line
        {
            return getopt($options, $longopts);
        }
        else if (isset($_REQUEST))  // web script
        {
            $found = array();

            $shortopts = preg_split('@([a-z0-9][:]{0,2})@i', $options, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            $opts = array_merge($shortopts, $longopts);

            foreach ($opts as $opt)
            {
                if (substr($opt, -2) === '::')  // optional
                {
                    $key = substr($opt, 0, -2);

                    if (isset($_REQUEST[$key]) && !empty($_REQUEST[$key]))
                        $found[$key] = $_REQUEST[$key];
                    else if (isset($_REQUEST[$key]))
                        $found[$key] = false;
                }
                else if (substr($opt, -1) === ':')  // required value
                {
                    $key = substr($opt, 0, -1);

                    if (isset($_REQUEST[$key]) && !empty($_REQUEST[$key]))
                        $found[$key] = $_REQUEST[$key];
                }
                else if (ctype_alnum($opt))  // no value
                {
                    if (isset($_REQUEST[$opt]))
                        $found[$opt] = false;
                }
            }

            return $found;
        }

        return false;
    }
    */


}