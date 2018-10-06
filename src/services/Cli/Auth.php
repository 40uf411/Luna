<?php

namespace Luna\services\Cli;

use Luna\Core\Authentication;

class Auth extends Authentication
{
    /**
     * @throws \Error
     */
    public function login()
    {
        $p = new Printer();
        $s = new Scanner();

        $p->printLn(NL . " You need to login to run this command!");
        $p->print(" Admin:");
        $user = $s->nextLine();
        $p->print(" Pass:");
        $pass = $s->nextLine();

        if (APP_DEFAULT_USERNAME == $user && APP_ROOT_PASSWD == $pass)
            return true;
        else
            die( NL . " Error! couldn't login." . NL);
    }
}