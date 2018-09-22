<?php

namespace Luna\services\Timer;


class Time
{
    private $year = 0;
    private $month = 0;
    private $day = 0;
    private $dow = 0;
    private $hour = 0;
    private $minute = 0;
    private $second = 0;

    private static function treat_day(?string $day)
    {
        switch ($day)
        {
            case 'Sunday':
                return 1;
                break;
            case 'Monday':
                return 2;
                break;
            case 'Tuesday':
                return 3;
                break;
            case 'Wednesday':
                return 4;
                break;
            case 'Thursday':
                return 5;
                break;
            case "Friday":
                return 6;
                break;
            case "Saturday":
                return 7;
                break;
        }
    }

    public function __construct($y = null, $m = null, $w = null, $d = null, $o = null, $h = null, $mi = null, $s = null)
    {
        $this->year = $y;
        $this->month = $m;
        $this->day = $d;
        $this->dow = $o;
        $this->hour = $h;
        $this->minute = $m;
        $this->second = $s;
    }

    public function __toString() : string
    {
        return $this->year . " - " . $this->month . ' - ' . $this->day . ' | ' . $this->hour . ' : ' . $this->minute .' : ' . $this->second ;
    }

    public function load($time)
    {
        $this->year     = intval( date('Y', $time) );
        $this->month    = intval( date('m', $time) );
        $this->day      = intval( date('d', $time) );
        $this->dow      = self::treat_day(date('l', $time));
        $this->hour     = intval( date('H', $time) );
        $this->minute   = intval( date('i', $time) );
        $this->second   = intval( date('s', $time) );

        return $this;
    }

    public function now()
    {
        $this->year     = intval( date('Y') );
        $this->month    = intval( date('m') );
        $this->day      = intval( date('d') );
        $this->dow      = self::treat_day(date('l'));
        $this->hour     = intval( date('H') );
        $this->minute   = intval( date('i') );
        $this->second   = intval( date('s') );

        return $this;
    }

    public function today()
    {
        $this->year     = intval( date('Y') );
        $this->month    = intval( date('m') );
        $this->day      = intval( date('d') );
        $this->dow      = self::treat_day(date('l'));

        return $this;
    }

    public function nextYear()
    {
        $this->year +=1;

        return $this;
    }
    public function nextMonth()
    {
        $this->month +=1;

        return $this;
    }
    public function tomorrow()
    {
        $this->day +=1;

        return $this;
    }
    public function nextHour()
    {
        $this->hour +=1;

        return $this;
    }
    public function nextMinute()
    {
        $this->minute +=1;

        return $this;
    }

    public function lastYear()
    {
        $this->year -=1;

        return $this;
    }
    public function lastMonth()
    {
        $this->month -=1;

        return $this;
    }
    public function yesterday()
    {
        $this->day -=1;

        return $this;
    }
    public function lastHour()
    {
        $this->hour -=1;

        return $this;
    }
    public function lastMinute()
    {
        $this->minute -=1;

        return $this;
    }

    public function get($format)
    {
        if ($format == 'unix')
        {
            return ( ( $this->second - date('s') ) + 60 * ( ( $this->minute - date('i') ) + 60 * ( ( $this->hour - date('H') ) + 24 * ( ( $this->day - date('d')) + ( 30 * ( ( $this->month - date('m')) + ( 12 * ( $this->year - date('Y') ) ) ) ) ) ) ) );
        }
        else
        {
            return date($format, $this->get('unix'));
        }
    }
    public function year()
    {
        return $this->year;
    }
    public function month()
    {
        return $this->month;
    }
    public function day()
    {
        return $this->day;
    }
    public function dow()
    {
        return $this->dow;
    }
    public function hour()
    {
        return $this->hour;
    }
    public function minute()
    {
        return $this->minute;
    }
    public function second()
    {
        return $this->second;
    }


    public function setYear(?int $year): void
    {
        $this->year = $year;
    }
    public function setMonth(?int $month): void
    {
        $this->month = $month;
    }
    public function setDay(?int $day): void
    {
        $this->day = $day;
    }
    public function setDow(?int $day): void
    {
        $this->dow = $day;
    }
    public function setHour(?int $hour): void
    {
        $this->hour = $hour;
    }
    public function setMinute(?int $minute): void
    {
        $this->minute = $minute;
    }
    public function setSecond(?int $second): void
    {
        $this->second = $second;
    }

    public function addYears(?int $year)
    {
        $this->year += $year;

        return $this;
    }
    public function addMonths(?int $month)
    {
        $this->month += $month;

        return $this;
    }
    public function addDays(?int $day)
    {
        $this->day += $day;

        return $this;
    }
    public function addHours(?int $hour)
    {
        $this->hour += $hour;

        return $this;
    }
    public function addMinutes(?int $minute)
    {
        $this->minute += $minute;

        return $this;
    }
    public function addSeconds(?int $second)
    {
        $this->second += $second;

        return $this;
    }

    public function subYears(?int $year)
    {
        $this->year -= $year;

        return $this;
    }
    public function subMonths(?int $month)
    {
        $this->month -= $month;

        return $this;
    }
    public function subDays(?int $day)
    {
        $this->day -= $day;

        return $this;
    }
    public function subHours(?int $hour)
    {
        $this->hour -= $hour;

        return $this;
    }
    public function subMinutes(?int $minute)
    {
        $this->minute -= $minute;

        return $this;
    }
    public function subSeconds(?int $second)
    {
        $this->second -= $second;

        return $this;
    }
}