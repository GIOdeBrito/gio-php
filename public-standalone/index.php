<?php

require __DIR__.'/../src/Core/Autoloader.php';

use GioPHP\Core\GioPHPApp;

$app = new GioPHPApp();
//$app->loader()->views = __DIR__."/Views";
$app->logger()->setLogPath(__DIR__.'/../logs');

$app->router()->get('/public-standalone/', function($req, $res)
{
	$viewData = [
		'title' => 'Home'
	];

	$res->render("Home", $viewData);
});

$app->router()->setNotFound('/public-standalone/404');
$app->router()->get('/public-standalone/404', function($req, $res)
{
	echo "<h1>Not Found 404</h1>";
});

$app->run();

?>