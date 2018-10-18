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
use Luna\lib\Ophelia\Memorise;

class UsersController extends Controller
{

    public function __invoke()
    {
        echo "hello world!";
    }

    public function login($data,Request $request,Response $response)
    {
        $s = \Luna\Services\Storage::disk();

        dump($s);
    }

    /**
     * @throws Error
     */
    public function upload()
    {
        dump(
            Memorise::key("name")
        );


        Memorise::key("name")->as("zinou")->forget_if_exist();
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
