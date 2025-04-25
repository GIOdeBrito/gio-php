<?php

require __DIR__.'/../src/Core/Autoloader.php';

use GioPHP\Core\GioPHPApp;

$app = new GioPHPApp();

$app->logger()->setLogPath(__DIR__.'/../logs');

$app->router()->get('/public-standalone/', function($req, $res)
{
	echo "<h1>GioPHP</h1>";
	echo "<p>This does not require Composer!</p>";
});

$app->router()->setNotFound('/public-standalone/404');

$app->router()->get('/public-standalone/404', function($req, $res)
{
	echo "<h1>Not Found 404</h1>";
});

$app->run();

?>