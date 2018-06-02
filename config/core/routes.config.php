<?php

namespace Luna\Config;

use Luna\ServiceProviders\HTTP;
use Luna\Service\Routing\simple\Router as SimpleRouter ;
use Luna\services\Routing\advanced\Router as Router;
use Luna\Core\View;

/***************************
 * you can add here all the routes
 * you need.
 *
 *
 * Notice: only one router will be in charge.
 *          to set which one go to [ config / web.config.php / 'router' ]
 *
 ***************************/

/**
 * simple router
 */

    SimpleRouter::add('home', 'Home');

    //RoutingService::add('url','controller', 'post');

/**
 *  Advanced router
 */

  Router::redirect("test","hi/5",5);
  Router::get('index/$id', 'index@home');
  Router::get('hi/$(int)id', 'hi@home');
  Router::get('hi', 'hi@home');
  Router::get('function', function (){
            echo $_SERVER['REQUEST_TIME_FLOAT'] ."<br>";
            echo $_SERVER['REQUEST_TIME'] ."<br>";
            echo HTTP::get_request_time()."<br>";
            echo is_int("5");
  });

//  Router::get('home/login/:id', function ($data){
//        echo $data['id'];
//  });

//  Router::get('about', function (){
//      // View::launch([
//      //           'template' => '<html> ... </html>
//      //      ]);
//
//          #or
//
//      // Loader::html('about');
//  });

// Router::get('login', 'login@user');
// Router::post('login', 'insert@user');
// Router::post('signin', 'signin@user');
