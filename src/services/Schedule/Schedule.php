<?php

namespace Luna\services;

use Luna\Core\Service;
use Luna\Helpers\Loader;
use Luna\services\Schedule\Task;
use Luna\services\Timer\Time;

class Schedule extends Service
{
    const SUNDAY = 1;
    const MONDAY = 2;
    const TUESDAY = 3;
    const WEDNESDAY = 4;
    const THURSDAY = 5;
    const FRIDAY = 6;
    const SATURDAY = 7;
    const DAY = [1, 2, 3, 4, 5, 6, 7];

    private $timeline = [];
    private $tmp;

    private static $records;

    public static function init($info = null)
    {
        parent::init($info);

        require_once "Task.php";

    }

    public static function config($info = null)
    {
        parent::config($info);

        Loader::config('services/schedule');
    }

    public function __construct()
    {
        self::$records[] = $this;
    }

    public function add(Task $task)
    {
        $this->tmp['task'] = $task;

        return $this;
    }

    public function every($days)
    {
        if (is_array($days))
        {
            foreach ($days as $day)
            {
                if ( in_array($day, [1, 2, 3, 4, 5, 6, 7] ) )
                {
                    $this->tmp['days'][] = $day;
                }
            }
        }
        else
        {
            if ( in_array($days, [1, 2, 3, 4, 5, 6, 7] ) )
            {
                $this->tmp['days'][] = $days;
            }
        }

        return $this;
    }

    public function at($times)
    {
        if (is_array($times))
        {
            foreach ($times as $time)
            {
                if ($time >= 0 && $time <= 23)
                {
                    $this->tmp['times'][] = $time;
                }
            }
        }
        if ($times >= 0 && $times <= 23)
        {
            $this->tmp['times'][] = $times;
        }

        return $this;
    }

    public function save()
    {
        //dump($this->tmp);
        //die();
        foreach ($this->tmp['days'] as $day)
        {
            if (isset($this->tmp['times']))
            {
                foreach ($this->tmp['times'] as $time)
                {
                    $this->timeline[$day][$time][] = $this->tmp['task'];
                }
            }
            else
            {
                $this->timeline[$day]['any'][] = $this->tmp['task'];
            }

        }
        $this->tmp = null;
    }

    private function launch($day, $time)
    {
        $time = intval($time);
        $day = intval($day);

        $tasks = [];
        if (!empty($this->timeline[$day][$time]))
        {
            $tasks = $this->timeline[$day][$time];
        }
        if (!empty($this->timeline[$day]["any"]))
        {
            $tasks = array_merge($tasks, $this->timeline[$day]["any"]);
        }
        return $tasks;
    }

    public static function execute(self $schedule = null, Time $time)
    {

        foreach (self::$records as $record)
        {
            $tasks = $record->launch($time->dow(), $time->hour());

            if ( ! empty($tasks))
            {

                foreach ($tasks as $task)
                {
                    $task->execute();
                }
            }
        }

        if (empty($schedule))
        {

        }
        else
        {

        }
    }
}