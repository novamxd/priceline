<?php
$blog = $app['controllers_factory'];
$blog->get('/', function() use($app)
{
	return $app['twig']->render('index.twig');
});

$app->mount('/', $blog);

?>