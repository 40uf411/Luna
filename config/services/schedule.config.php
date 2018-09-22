<?php

use Luna\services\Schedule;
use Luna\services\Schedule\Task;

$s = new Schedule();

$s->add((new Task(function (){
    //dump($_SERVER);
    //echo $what_you_want = $path['basename'];
})))
    ->every(Schedule::DAY)
    ->at(07)
    ->save();
