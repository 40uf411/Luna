<?php


class NoRouteFoundError extends  Luna_Error
{

    public function getMessage()
    {
        echo parent::getMessage() . "no route has been registered";
    }
}