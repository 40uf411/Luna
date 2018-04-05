<?php

namespace Luna\core;

use Luna\Helpers\Loader;

abstract class Controller
{

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

        //return new $model($pram);

    }

    /**
     * @param $view
     * @param  $data
     *
     *  function to load a view
     */
    protected function view($view, $data = [])
    {
        require_once VIEW_PATH . $view . ".view.php";
    }

}