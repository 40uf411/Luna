<?php

class twig_viewDriver implements viewDriver
{

    protected $loader;

    protected $config;

    protected $engine;

    private $data = [];

    public function __construct($config)
    {

        $this->config = $config;

        $this->loader = new Twig_Loader_Filesystem(VIEWS_PATH  );

        $this->engine =  new Twig_Environment($this->loader, $this->config);
    }

    public function set($key, $value = null, ...$data)
    {
        $this->data[$key] = $value;
    }

    public  function render($file, $data = [])
    {
        return $this->engine->render($this->config['sub_domain'] . DS . $file . $this->config['extension'],array_merge($data, $this->data) );
    }
}