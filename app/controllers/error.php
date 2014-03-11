<?php
use Symfony\Component\HttpFoundation\Response;

//simple error handler, taken right from silex
$app->error(function (\Exception $e, $code) 
{
    switch ($code) 
	{
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message);
});

?>