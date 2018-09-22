<?php

namespace Luna\services\Router;


class Route
{
    private $name;
    private $url;
    private $action;
    private $callback;
    private $pattern;
    private $time = 3;

    private $view_driver;

    private $middleware = null;

    public function __construct($action, $callback = null, $time = 3)
    {
        $this->action = $action;
        $this->callback = $callback;
        $this->time = $time;
        return $this;
    }

    public function __destruct(){}


    public function __call($name, $arguments)
    {
        ErrorHandler::add('CallToUndefinedFunction');
    }

    public function __debugInfo(){
        //return debug_backtrace();
    }

    public function __get($name)
    {
        ErrorHandler::add('GetUndefinedAttribute');

    }

    public function __set($name, $value)
    {
        ErrorHandler::add('SetUndefinedAttribute');

    }

    #

    public function url($url)
    {
        $this->url = $url;
    }

    public function time($time)
    {
        $this->time = $time;

        return $this;
    }
    public function getTime():? int
    {
        return $this->time;
    }

    public function action($action)
    {
        $this->action = $action;

        return $this;
    }
    public function getAction()
    {
        return $this->action;
    }

    public function callback($callback)
    {
        $this->callback = $callback;

        return $this;
    }
    public function getCallback()
    {
        return $this->callback;
    }

    public function name($name)
    {
        $this->name = $name;

        return $this;
    }
    public function getName()
    {
        return $this->name;
    }

    public function middleware($middleware)
    {
        $this->middleware[] = $middleware;

        return $this;
    }
    public function getMiddleware()
    {
        return $this->middleware;
    }


    public function pattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }
    public function getPattern($pattern = null)
    {
        if (isset($this->pattern[$pattern]))
            return $this->pattern[$pattern];

        else
            return false;

    }

    public function view($view, $driver = null)
    {
        $this->action = "view";

        $this->callback = $view;

        return $this;
    }

    public function getDriver()
    {
        return $this->view_driver;
    }
}