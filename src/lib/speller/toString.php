<?php

namespace Luna\lib\speller;


class toString
{
    public static function treat($number)
    {
        if (is_integer($number))
        {
            if ($number === 0)
                return "zero";
            else
                return self::spell($number);
        }
    }

    private static function num($i,$n,$k)
    {
        $s = "";
        if($k ==0)
        {
            switch($i)
            {
                case 0 : $s = "ten ". $s ; break;
                case 1 : $s = "eleven ". $s ;break;
                case 2 : $s = "twelve ". $s ;break;
                case 3 : $s = "thirteen ". $s ;break;
                case 4 : $s = "fourteen ". $s ;break;
                case 5 : $s = "fifteen ". $s ;break;
                case 6 : $s = "sixteen ". $s ;break;
                case 7 : $s = "seventeen ". $s ;break;
                case 8 : $s = "eighteen ". $s ;break;
                case 9 : $s = "nineteen ". $s ;break;
            }
        }

        else if($k ==1)
        {
            switch($n)
            {
                case 0 : switch($i){
                    case 1 : $s = "one ". $s ; break;
                    case 2 : $s = "two ". $s ;break;
                    case 3 : $s = "three ". $s ;break;
                    case 4 : $s = "four ". $s ;break;
                    case 5 : $s = "five ". $s ;break;
                    case 6 : $s = "six ". $s ;break;
                    case 7 : $s = "seven ". $s ;break;
                    case 8 : $s = "eight ". $s ;break;
                    case 9 : $s = "nine ". $s ;break;
                }break;
                case 1 : switch($i){
                    case 1 : $s = "ten ". $s ;break;
                    case 2 : $s = "twenty ". $s ;break;
                    case 3 : $s = "thirty ". $s ;break;
                    case 4 : $s = "fourty ". $s ;break;
                    case 5 : $s = "fifty ". $s ;break;
                    case 6 : $s = "sixty ". $s ;break;
                    case 7 : $s = "seventy ". $s ;break;
                    case 8 : $s = "eighty ". $s ;break;
                    case 9 : $s = "ninety ". $s ;break;
                }break;

                case 2 : $s = self::num($i,0,1)."hundred " . $s ; break;
                default :
                    if(($n>=3)&&($n<=5)){
                        $s = self::num($i,$n-3,1);
                    }
                    else{
                        if(($n>=6)&&($n<=8)){
                            $s = self::num($i,$n-6,1);
                        }
                    }break;
            }
        }
        return $s;
    }

    private static function e($i)
    {
        $j=1;

        for($l=1;$l<=$i;$l++)

            $j=$j*10 ;

        return $j;
    }
    private static function spell($x)
    {
        $k=1;

        $r = "";

        if($x>999999)
        {
            $i= intval($x/1000000);

            $r = $r . self::spell($i);

            $r = $r . "millions ";

            $x=$x-$i*1000000;
        }
        elseif($x>999)
        {
            $i = intval($x/1000);

            $r = $r . self::spell($i);

            $r = $r . "thousand ";

            $x=$x-$i*1000;
        }

        while($x>0)
        {
            $i=$x;

            $n=0;

            while($i>=10)
            {
                $i= intval($i/10);

                $n++;
            }

            if(($n==1)&&($i==1))
            {
                $k=0;

                $i=$x-10;

                $x=0;

                $r = $r . self::num($i,$n,$k);
            }
            else
            {
                $r = $r . self::num($i,$n,$k);

                $x=$x-$i*self::e($n);
            }
        }

        return $r;
    }
}