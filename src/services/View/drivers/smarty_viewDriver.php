<?php


class smarty_viewDriver extends Smarty implements viewDriver
{
    protected $loader;

    protected $config;

    protected $engine;

    public function __construct($config)
    {
        parent::__construct();

        $this->config = $config;

        //dump($this->config['config_dir'] . DS);
        //die();

        $this->setTemplateDir( VIEWS_PATH . DS . $this->config['sub_domain'] . DS );
        $this->setCompileDir($this->config['compile_dir'] . DS);
        $this->setConfigDir($this->config['config_dir'] . DS);
        $this->setCacheDir($this->config['cache_dir'] . DS);

        $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
    }

    public function set($key, $value = null, ...$data)
    {
        $this->assign($key, $value,$data[0]);
    }

    public  function render($file, $data = null)
    {
        foreach ($data as $item => $value)
        {
            $this->assign($item, $value);
        }
        /*
        $this->assign("Name", "Fred Irving Johnathan Bradley Peppergill", true);
        $this->assign("FirstName", array("John", "Mary", "James", "Henry"));
        $this->assign("LastName", array("Doe", "Smith", "Johnson", "Case"));
        $this->assign("Class", array(array("A", "B", "C", "D"), array("E", "F", "G", "H"), array("I", "J", "K", "L"),
            array("M", "N", "O", "P")));

        $this->assign("contacts", array(array("phone" => "1", "fax" => "2", "cell" => "3"),
            array("phone" => "555-4444", "fax" => "555-3333", "cell" => "760-1234")));

        $this->assign("option_values", array("NY", "NE", "KS", "IA", "OK", "TX"));
        $this->assign("option_output", array("New York", "Nebraska", "Kansas", "Iowa", "Oklahoma", "Texas"));
        $this->assign("option_selected", "NE");*/

        return $this->fetch($file . $this->config['extension'] );
    }
}