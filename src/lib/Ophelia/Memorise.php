<?php

namespace Luna\lib\Ophelia;


use Luna\Andromeda\Andromeda;

class Memorise
{
    private static $memo_configed = false;

    public static function _($key)
    {
        return new Memory($key);
    }

    /**
     * @param Memory $memo
     * @return bool
     * @throws \Error
     * @throws \Exception
     */
    public static function save(Memory $memo)
    {

        $db = Andromeda::connect(["name" => "Ophelia" , "user" => "memo", "pass" => "mind"]);

        if ($db->from("memory")->exist($memo->getKey())  and  ! $memo->isForget())
            Error("Oops! it looks like there is a memory with same name in here, and we are not allowed to forget it.");

        else
        {
            $m = [
                "value" => $memo->getValue(),
                "expire_date" => $memo->getExpireDate(),
                "secure" => $memo->isSecure(),
                "pass" => $memo->getPass()
            ];

            $db->insert($memo->getKey(),$m)->inTo("memory")->override()->exec();

            return true;
        }
    }
}