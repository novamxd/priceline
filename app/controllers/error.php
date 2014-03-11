<?php
//simple error handler, taken right from silex but integrated with twig
$app->error(function(\Exception $e, $code) use($app)
{
    switch ($code) 
	{
        case 404:
            return $app['twig']->render('404.twig');
            break;
        default:
            return $app['twig']->render('500.twig',array("e"=>$e));
    }
});
?>