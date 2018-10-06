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

Router::home("Users@upload");

Router::any('/hi/{name/hi',function($data){
   dump($data);
})->pattern([
    "name" => "/ali|7a9o/"
]);

/*
Router::home()->view("index");




Router::any('/hi/$name/$(int)age', function($data){

    echo "hello " . $data['name'];
    echo "<br>";
    echo "your age is " . $data['age'];

});
;

/*
Router::home('Users@login')->name('home');

Router::get('/hi/$name')
    ->view('test')
    ->name('hello')
    ->pattern(['name'=>'/karim|ali/']);

Router::any('/u', "Users@upload");

Router::any('/upload',"Users@upload");

Router::get('hi/zino',function (){
    echo "zeno";
});*/