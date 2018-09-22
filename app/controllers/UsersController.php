<?php

use Luna\Core\Controller;
use Luna\Services\Http\{Request, Response};
use Luna\Services\{
    Cookie,
    Timer\Time,
    Schedule,
    Schedule\Task,
    Storage
};

class UsersController extends Controller
{

    public function login($data,Request $request,Response $response)
    {
        $s = \Luna\Services\Storage::disk();

        dump($s);
    }

    public function upload()
    {
        dump([
            "ali" => "ali",
            01=> 15
        ]);
    }
}

class user
{
    private $userId;
    protected $jobTitleName;

    /**
     * @return mixed
     */
    public function __get($var)
    {
        return [$var];
    }
}
