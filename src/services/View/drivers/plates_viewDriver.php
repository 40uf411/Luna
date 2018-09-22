<?php

class plates_viewDriver implements viewDriver
{

    protected $engine;

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;

        $this->engine = new League\Plates\Engine( VIEWS_PATH . DS . $this->config['sub_domain']);
    }

    public function set($key, $value = null, ...$data)
    {
        $this->engine->addData([$key => $value], $data[0]);
    }

    public function render($file, $data = null)
    {
        return $this->engine->render($file, $data);
    }
}