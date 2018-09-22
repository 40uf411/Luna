<?php

namespace Luna\services\Cli;


class Printer
{

    /**
     * @param string $string
     * @param array $theme
     * @param bool $center
     * @param int $width
     * @return Color|string
     * @throws \Error
     */
    public function render($string = "", $theme = [], bool $center  = false, int $width = 80)
    {
        if (! is_cli())
        {
            error("Class Printer can only used in CLI.");
        }

        $theme = is($theme,"array")? $theme: [$theme];

        if(in_array('capitalize',$theme))
        {
            $string = ucfirst($string);
        }
        elseif(in_array('uncapitalize',$theme))
        {
            $string = lcfirst($string);
        }
        elseif (in_array('upper',$theme))
        {
            $string = strtoupper($string);
        }
        elseif (in_array('lower',$theme))
        {
            $string = strtolower($string);
        }

        $c = new Color($string);

        foreach ($theme as $item)
        {
            if ($c->isStyleExists($item))
            {
                $c->apply($item);
            }
        }

        return $center ? $this->center($c, $width) : $c;
    }

    /**
     * @param string $string
     * @param array $theme
     * @param bool $center
     * @param int $width
     * @return Color|string
     * @throws \Error
     */
    public function print($string = "", $theme = [], bool $center  = false, int $width = 80)
    {
        echo $this->render($string,$theme,$center,$width);
    }

    /**
     * @param string $string
     * @param array $theme
     * @param bool $center
     * @param int $width
     * @throws \Error
     */
    public function printLn($string = "", $theme = [], bool $center  = false, int $width = 80)
    {
        $this->print($string,$theme,$center,$width);
        echo NL;
    }

    /**
     * @param string $string
     * @param array $theme
     * @param bool $center
     * @param int $width
     * @throws \Error
     */
    public function printSl($string = "", $theme = [], bool $center  = false, int $width = 80)
    {
        $this->print("\r" . $string,$theme,$center,$width);
    }

    /**
     * @param $text
     * @param int $width
     * @return string
     * @throws \Error
     */
    public function center($text, $width = 80)
    {
        if (! is_cli())
        {
            error("Class Printer can only used in CLI.");
        }

        $centered = '';
        foreach (explode(PHP_EOL, $text) as $line) {
            $line = trim($line);
            $lineWidth = strlen($line) - mb_strlen($line, 'UTF-8') + $width;
            $centered .= str_pad($line, $lineWidth, ' ', STR_PAD_BOTH) . PHP_EOL;
        }
        return trim($centered, PHP_EOL);
    }

}