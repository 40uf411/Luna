<?php

namespace Luna\core;

use Luna\Helpers\Loader;
use Luna\Core\View;

abstract class Controller
{

    public static function init()
    {

    }

    /**
     * @param null $pram
     * @return mixed
     *
     * default function (to be called if no function is set)
     */
    public abstract function index($pram = null);

    /**
     * @param $model
     * @return mixed
     *
     * function to load a model
     */
    protected function model($model, $pram = null)
    {
        Loader::model($model);
    }

    /**
     * @param  $data
     *
     *  function to load a view
     */
    protected function view($data = [])
    {
            View::launch($data);
    }
}