<?php

use Symfony\Component\HttpFoundation\Response;

$blog = $app['controllers_factory'];

//register our blog archives
$app['twig']->addGlobal('archive_years', getBlogArchiveMenu());

$blog->get('/', function() use($app,&$dbh)
{
	//setup our statement
	$statement = $dbh->prepare("SELECT post.*, user.name FROM post INNER JOIN user ON (user.id = post.user_id) ORDER BY post.posted_date DESC LIMIT 0,10");
	
	//placeholder array
	$recent = array();	
	
	//run on the database
	if( $statement->execute() )
	{
		//get our records
		$recent = $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	
	return $app['twig']->render('blog/index.twig',array(
		"recent" => $recent
	));
});

$blog->get('/post/{id}', function($id) use($app,&$dbh)
{
	//do some id validation
	if(! is_numeric($id) )
	{
		$app->abort(404);
	}
	
	//setup our statement
	$statement = $dbh->prepare("SELECT post.*, user.name FROM post INNER JOIN user ON (user.id = post.user_id) WHERE post.id = :id");	
	
	//run on the database
	if(! $statement->execute(array("id" => $id)) )
	{
		$app->abort(404);
	}
	
	//get our record
	$post = $statement->fetch();
	
	//did we get a hit?
	if( !$post || !isset($post["id"]) || !is_numeric($post["id"]) )
	{
		$app->abort(404);
	}
	
	return $app['twig']->render('blog/post.twig',array(
		"post" => $post
	));
});

$blog->get('/archive/{year}/{month}', function($year,$month) use($app,&$dbh)
{
	//place holder
	$posts = array();
	
	//validation flag
	$valid = true;
	
	//do some id validation
	if( !is_numeric($year) || !is_numeric($month) )
	{
		$valid = false;
	}
	
	//get the current date
	$archive_date = time();
	
	if( $valid )
	{
		//do we have a valid date?
		if( !checkdate($month,01,$year) )
		{
			$valid = false;
		}
		else
		{
			//get the date of the archive for formatting
			$archive_date = mktime(0,0,0,$month,01,$year);
		}
	}
	
	if( $valid )
	{
		//setup our statement
		$statement = $dbh->prepare("SELECT post.*, user.name FROM post INNER JOIN user ON (user.id = post.user_id) WHERE YEAR(posted_date) = :year AND MONTH(posted_date) = :month ORDER BY posted_date DESC");	
		
		//run on the database
		if(! $statement->execute(array("year" => $year,"month" => $month)) )
		{
			$valid = false;
		}
		
		if( $valid )
		{
			//get our records
			$posts = $statement->fetchAll(PDO::FETCH_ASSOC);
		}		
	}
	
	return $app['twig']->render('blog/archive.twig',array(
		"posts" => $posts,
		"archive_date" => $archive_date
	));
});

$blog->get('/randomize', function() use($app,&$dbh)
{
	//setup our statement
	$statement = $dbh->prepare("UPDATE post SET posted_date = CURRENT_TIMESTAMP - INTERVAL FLOOR(RAND() * 1095) DAY");
	
	//run on the database
	 $statement->execute();
	
	return "";
});

function getBlogArchiveMenu()
{
	global $dbh;
	
	//placeholder menu
	$menu = array();
	
	//setup our statement
	$statement = $dbh->prepare("SELECT DISTINCT YEAR(posted_date) AS `year` FROM post ORDER BY posted_date DESC");	
	
	//attempt to get our years
	if( $statement->execute() )
	{
		//get our years
		$years = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		//loop through our years
		foreach( $years as $index => $year )
		{
			//setup our statement to get the months
			$year_statement = $dbh->prepare("SELECT DISTINCT MONTH(posted_date) AS `month` FROM post WHERE YEAR(posted_date) = :year ORDER BY posted_date DESC");
			
			//placeholder array
			$years[$index]["months"] = array();
			
			//attempt to run on the database
			if( $year_statement->execute(array("year" => $year["year"] )) )
			{
				$years[$index]["months"] = $year_statement->fetchAll(PDO::FETCH_ASSOC);
			}
		}
		
		//set our menu
		$menu = $years;
	}
	
	return $menu;
}

$app->mount('/', $blog);

?>