<?php

use Symfony\Component\VarDumper\{
    Cloner\VarCloner, Dumper\CliDumper, Dumper\HtmlDumper, VarDumper
};

VarDumper::setHandler(function ($var){
    $cloner = new VarCloner;
    $htmlDumper = new HtmlDumper;

    $htmlDumper->setStyles([
        'default' => 'background-color:transparent; color:#FF8400; line-height:1.2em; font:14px -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,Arial,sans-serif; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: break-all',
        'num' => 'font-weight:bold; color:#1299DA',
        'const' => 'font-weight:bold',
        'str' => 'font-weight:bold; color:#56DB3A',
        'note' => 'color:#1299DA',
        'ref' => 'color:#A0A0A0',
        'public' => 'color:#525252;font-weight: bold; font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,Arial,sans-serif',
        'protected' => 'color:#333 ;font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,Arial,sans-serif',
        'private' => 'color:#444 ;font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,Arial,sans-serif',
        'meta' => 'color:#B729D9',
        'key' => 'color:#56DB3A',
        'index' => 'color:#1299DA',
        'ellipsis' => 'color:#FF8400',
    ]);
    $cliDumper =  new CliDumper();

    $cliDumper->setStyles([
        'default' => '38;5;208',
        'num' => '1;38;5;38',
        'const' => '1;38;5;208',
        'str' => '1;32;5;113',
        'note' => '38;5;38',
        'ref' => '38;5;247',
        'public' => '',
        'protected' => '',
        'private' => '',
        'meta' => '38;5;170',
        'key' => '38;5;113',
        'index' => '38;5;38',
    ]);
    $dumper = php_sapi_name() === 'cli' ?  $cliDumper : $htmlDumper ;

    $dumper->dump($cloner->cloneVar($var));
});