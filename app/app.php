<?php

require_once __DIR__ . '/../vendor/autoload.php';

//create our application
$app = new Silex\Application();

//enable debugging
$app['debug'] = true;

//register twig as our templating enging
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
    'twig.options' => array('debug' => $app['debug'])
));

//database
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

//model path
$db_paths = array(__DIR__."/models");

// the connection configuration
$db_params = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'secnem_priceline',
    'password' => 'Xy4M34L0FZgU',
    'dbname'   => 'secnem_priceline',
	'host'	   => 'secnem.com'
);

//create our entity manager
$config = Setup::createAnnotationMetadataConfiguration($db_paths, $app['debug']);
$entity_manager = EntityManager::create($db_params, $config);

//include our blog controller routes
include 'controllers/blog.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;

ConsoleRunner::createHelperSet($entity_manager);

//run
$app->run();

?>