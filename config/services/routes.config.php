<?php
use Luna\services\Router;
use Luna\services\Http\{Response, Request};

/***************************
 * you can add here all the routes
 * you need.
 *
 *
 * Notice: only one router will be in charge.
 *          to set which one go to [ config / web.config.php / 'router' ]
 *
 ***************************/

Router::home('Users@login')->name('home');



Router::get('/hi/$name')
    ->view('test')
    ->name('hello')
    ->pattern(['name'=>'/karim|ali/']);

Router::any('/u')->view("index");

Router::any('/upload',"Users@upload");