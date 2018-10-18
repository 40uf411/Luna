<?php

namespace Luna\lib\Ophelia;

use Luna\Andromeda\Andromeda;
use Luna\services\Crypt;
use Luna\services\Timer\Time;

class Remember
{
    public static function _($key, $password = "")
    {
        $db = Andromeda::connect(["name" => "Ophelia" , "user" => "memo", "pass" => "mind"]);

        if ($db->from("memory")->exist($key))
        {
            $m =  $db->from("memory")->where("key", "==", $key)->fetch();

            $t = now();

            if(
                ($m['expire_date']['year'] > $t->year()) or
                ($m['expire_date']['year'] == $t->year() and $m['expire_date']['month'] > $t->month()) or
                ($m['expire_date']['year'] == $t->year() and $m['expire_date']['month'] == $t->month() and $m['expire_date']['day'] > $t->day()) or
                ($m['expire_date']['year'] == $t->year() and $m['expire_date']['month'] == $t->month() and $m['expire_date']['day'] == $t->day() and $m['expire_date']['hours'] > $t->hour()) or
                ($m['expire_date']['year'] == $t->year() and $m['expire_date']['month'] == $t->month() and $m['expire_date']['day'] == $t->day() and $m['expire_date']['hours'] == $t->hour()) or
                ($m['expire_date']['year'] == $t->year() and $m['expire_date']['month'] == $t->month() and $m['expire_date']['day'] == $t->day() and $m['expire_date']['hours'] == $t->hour() and $m['expire_date']['minutes'] > $t->minute())
            )
            {
                if($m['secure'] and Crypt::password_verify($password, $m['pass']))
                {
                    return $m['value'];
                }
            }
            else
                $db->delete()->from("memory")->where("key", "==", $key)->exec();

        }

        return null;
    }
}