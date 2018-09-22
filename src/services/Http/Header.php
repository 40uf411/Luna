<?php

namespace Luna\services\Http;


class Header
{
    /**
     * @var string
     */
    private $string;

    public function __construct($key, $value = null)
    {
        if ($value)
            $this->string = "$key: $value";
        else
            $this->string = "$key";
    }

    public function __toString()
    {
        return $this->string;
    }
}