<?php

namespace Luna\services\Cli;


class Progress
{
    private $actualProgress = 0;
    private $progressJump = 10;
    private $maxProgress = 100;
    private $progressBar = true;
    private $percentage = true;
    private $status = true;
    private $new_line = true;

    private $last_step_msg_length = 0;

    private $closedAtt = [];

    public function __set($variable, $value)
    {
        if (in_array($variable,['progressBar', 'percentage', 'new_line','maxProgress', 'progressJump', "status"]) and ! in_array($variable, $this->closedAtt))
        {
            $this->$variable = $value;

            $this->closedAtt[] = $variable;
        }
    }

    public function done()
    {
        $this->progressJump = 10;
        $this->maxProgress = 100;
        $this->progressBar = true;
        $this->percentage = false;
        $this->style = 0;
        $this->closedAtt = [];
    }

    /**
     * @param $message
     * @param null $progress
     * @throws \Error
     */
    public function step($message, $progress = null, $status = null, $status_style = [])
    {
        if (isset($progress))
        {
            $progress = intval($progress);
            if ( $progress < $this->actualProgress)
                error("invalid progress number.");
        }
        else
        {
            $progress = $this->actualProgress + $this->progressJump;
        }
        $status_style = (!is($status_style, "ary")) ? [$status_style] : $status_style;
        $p = new Printer();

        $s = (isset($this->new_line) and ! $this->new_line)? "\r" : NL;
        $s =  (isset($this->progressBar) and $this->progressBar)? $s . "|" . $this->calc_prog($progress) . "|" :$s ;
        $s =  (isset($this->percentage) and $this->percentage)? $s . "[" . $progress . "%]" :$s ;
        $s =  (isset($this->status) and $this->status)? $s . "[" . $p->render($status,$status_style)  . "]" :$s ;

        if (strlen($message) < $this->last_step_msg_length)
        {
            do
            {
                $message .= " ";
            }while(strlen($message) < $this->last_step_msg_length);
        }
        else
            $this->last_step_msg_length = strlen($message);
        $s = $s . " $message";


        $p->print($s);

    }

    /**
     * @param $progress
     * @return string
     * @throws \Error
     */
    private function calc_prog($progress)
    {
        $p = 20 * $progress / $this->maxProgress;
        $s = "";
        $b = (new Printer())->render(" ",['bg_white']);
        for ($i = 0; $i < $p; $i++)
            $s = $s . $b;
        for ($i = $p; $i < 20; $i++)
            $s = $s . ".";
        return $s;
    }
}