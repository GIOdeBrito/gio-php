<?php

require __DIR__.'/../src/Core/Autoloader.php';

use GioPHP\Core\GioPHPApp;

$app = new GioPHPApp();
$app->loader()->views = __DIR__."/Views";

$app->router()->get('/public-standalone/', function($req, $res)
{
	$viewData = [
		'title' => 'Home'
	];

	$res->render("Home", $viewData);
});

$app->router()->get('/public-standalone/404', function($req, $res)
{
	echo "<h1>Not Found 404</h1>";
});

$app->router()->set404('/public-standalone/404');

$app->run();

?>