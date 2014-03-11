<?php
$blog = $app['controllers_factory'];
$blog->get('/', function() use($app,&$dbh)
{
	$recent = $dbh->prepare("SELECT * FROM post ORDER BY posted_date DESC LIMIT 1,10");
	return $app['twig']->render('index.twig',array(
		"recent" => $recent
	));
});

$app->mount('/', $blog);

?>