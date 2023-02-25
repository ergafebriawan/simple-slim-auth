<?php

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['view'] = function ($container) {
        $view = new Twig(__DIR__.'/../view/', [
            'cache' => false
        ]);
    
        $router = $container->get('router');
        $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
        $view->addExtension(new TwigExtension($router, $uri));
    
        return $view;
    };

    //database
    $container['db'] = function ($c){
        $settings = $c->get('settings')['db'];
        $conn = new Mysqli($settings["host"], $settings["user"], $settings["pass"], $settings["dbname"]);  
        return $conn;
    };

    //directory
    $container['dir'] = function ($c){
        $settings = $c->get('settings')['directory'];  
        return $settings;
    };

    //flash
    $container['flash'] = function(){
        return new \Slim\Flash\Messages();
    };
};
