<?php

//load our bootstrap
require_once 'bootstrap.php';

//create our application
$app = new Silex\Application();

//enable debugging
$app['debug'] = true;

//register twig as our templating enging
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
    'twig.options' => array('debug' => $app['debug'])
));

//include our blog controller routes
include 'controllers/blog.php';

//run
$app->run();

?>