<?php

namespace Luna\services\Cli;


class Color
{
    const ESC = "\033[";
    protected const ESC_SEQ_PATTERN = "\033[%sm";

    protected const STYLES = [
        'reset'            => '0',
        'bold'             => '1',
        'dark'             => '2',
        'italic'           => '3',
        'underline'        => '4',
        'blink'            => '5',
        'reverse'          => '7',
        'concealed'        => '8',

        'default'          => '39',
        'black'            => '30',
        'red'              => '31',
        'green'            => '32',
        'yellow'           => '33',
        'blue'             => '34',
        'magenta'          => '35',
        'cyan'             => '36',
        'light_gray'       => '37',

        'dark_gray'        => '90',
        'light_red'        => '91',
        'light_green'      => '92',
        'light_yellow'     => '93',
        'light_blue'       => '94',
        'light_magenta'    => '95',
        'light_cyan'       => '96',
        'white'            => '97',

        'bg_default'       => '49',
        'bg_black'         => '40',
        'bg_red'           => '41',
        'bg_green'         => '42',
        'bg_yellow'        => '43',
        'bg_blue'          => '44',
        'bg_magenta'       => '45',
        'bg_cyan'          => '46',
        'bg_light_gray'    => '47',

        'bg_dark_gray'     => '100',
        'bg_light_red'     => '101',
        'bg_light_green'   => '102',
        'bg_light_yellow'  => '103',
        'bg_light_blue'    => '104',
        'bg_light_magenta' => '105',
        'bg_light_cyan'    => '106',
        'bg_white'         => '107',
    ];


    protected $wrapped = '';
    protected $initial = '';

    /**
     * Color constructor.
     * @param string $string
     * @throws \Error
     */
    public function __construct($string = '')
    {
        if (! is_cli())
        {
            error("Class Color can only used in CLI.");
        }
        $this->setInternalState($string);
    }

    public function __invoke($string)
    {
        return $this->setInternalState($string);
    }

    public function __call($method, $args)
    {
        if (count($args) >= 1) {
            return $this->apply($method, $args[0]);
        }

        return $this->apply($method);
    }

    public function __get($name)
    {
        return $this->apply($name);
    }

    public function __toString()
    {
        return $this->wrapped;
    }

    protected function style($style, $text)
    {
        if ($this->isStyleExists($style)) {
            return $this->applyStyle($style, $text);
        }

        if (preg_match('/^((?:bg_)?)color\[([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\]$/', $style, $matches)) {
            $option = $matches[1] == 'bg_' ? 48 : 38;
            return $this->buildEscSeq("{$option};5;{$matches[2]}") . $text . $this->buildEscSeq(self::STYLES['reset']);
        }

        return $this->wrapped;
    }

    public function isStyleExists($style)
    {
        return array_key_exists($style, self::STYLES);
    }

    public function apply($style, $text = null)
    {
        if ($text === null) {
            $this->wrapped = $this->style($style, $this->wrapped);
            return $this;
        }

        return $this->style($style, $text);
    }

    protected function applyStyle($style, $text)
    {
        return $this->buildEscSeq(self::STYLES[$style]) . $text . $this->buildEscSeq(self::STYLES['reset']);
    }

    protected function buildEscSeq($style)
    {
        return sprintf(self::ESC_SEQ_PATTERN, $style);
    }

    protected function setInternalState($string)
    {
        $this->initial = $this->wrapped = (string) $string;

        return $this;
    }

    protected function stripColors($text)
    {
        return preg_replace('/' . preg_quote(self::ESC) . '\d+m/', '', $text);
    }

    public function clean($text = null)
    {
        if ($text === null) {
            $this->wrapped = $this->stripColors($this->wrapped);
            return $this;
        }

        return $this->stripColors($text);
    }


}