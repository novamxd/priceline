<?php

//add our auto loader
require_once __DIR__ . '/../vendor/autoload.php';

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
?>