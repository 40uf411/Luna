<?php

namespace Luna\services\Cli;

abstract class Command
{
    protected $options = [];

    protected $set_options;

    public function __construct()
    {   
    }

    public abstract function __invoke($arg = null);

    public abstract function __call($function, $args);

    public abstract function help();

    public function setup()
    {
        $this->setOption("help", "show the help guid page.","h","help");
    }
    
    /**
     * @param $option
     * @param $description
     * @param $short
     * @param null $long
     */
    public final function setOption($option, $description, $short, $long = null): void
    {
        $this->options[$option] = [
            "description" => $description,
            "short" => $short,
            "long" => $long
        ];
    }

    public final function checkOpt($option)
    {
        return isset($this->set_options[$option]);
    }

    /**
     * @param $parameters
     * @param $passedParameters
     * @return bool|mixed
     * @throws \Error
     */
    public final function requiredArgs($parameters, $passedParameters)
    {
        if( hasKeys($passedParameters, $parameters) )
            return true;
        else
            error("few arguments passed.");

        return false;
    }


    public final function opt($option)
    {
        if( $this->hasOpt($option) )
        {
            $short = $this->options[$option]["short"];
            $long = $this->options[$option]["long"];

            if( isset($short) && isset($this->set_options[$short]) )
            {
                return $this->set_options[$short];
            }
            elseif ( isset($long) && isset($this->set_options[$long]) )
            {
                return $this->set_options[$long];
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * @param $option
     * @return bool
     */
    public final function isOpt($option)
    {
        foreach ($this->options as $opt)
        {
            if ($opt['short'] == $option || $opt['long'] == $option)
                return true;
        }
        return false;
    }

    /**
     * @param array $options
     */
    public final function renderOpt( array $options)
    {
        $a = [];

        foreach ($options as $option => $item)
        {
            if ($this->isOpt($option))
                $a[$option] = $item;
        }
        $this->set_options =  $a;
    }

    public final function hasOpt( $option )
    {
        if( isset($this->options[$option]) )
        {
            $short = $this->options[$option]["short"];
            $long = $this->options[$option]["long"];

            return ( $this->checkOpt($short) || $this->checkOpt($long) );
        }
        else
            return false;
        
    }

    /**
     * @throws \Error
     */
    protected final function getOptGuide()
    {
        $p = new Printer();
        $t = new Table(["name", "short", "long", "description"]);
        $t->setChar("col", "");
        $t->setChar("line", "");

        $s = $p->render(NL . " Options:",["blue", "bold"]);

        foreach ($this->options as $option => $value)
        {
            $t->insert(["name" => $option, "short" => "-" . $value['short'], "long" => "--" . $value['long'], "description" => $value['description']]);
        }


        return $s . $t->render();
    }
}