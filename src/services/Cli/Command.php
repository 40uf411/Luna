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
     * @param $option
     * @return bool
     */
    public final function opt($option)
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
            if ($this->opt($option))
                $a[$option] = $item;
        }
        $this->set_options =  $a;
    }

    /**
     * @throws \Error
     */
    protected final function getOptGuide()
    {
        $p = new Printer();
        $s = $p->render(NL . "Options:",["bg_white","blue"]) . NL;

        foreach ($this->options as $option => $value)
        {
            $s .= $p->render("  " . $option . "\t\t" . "-" . $value['short'] . "  " . "-" . $value['long'] . "\t" . $value['description']) . NL;
        }

        return $s;
    }
}