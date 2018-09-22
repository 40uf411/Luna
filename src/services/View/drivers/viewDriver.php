<?php

interface viewDriver
{
    public function set($key, $value = null, ...$data);

    public function render($file, $data = []);
}