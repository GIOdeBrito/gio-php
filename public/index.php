<?php

require __DIR__.'/../vendor/autoload.php';

use GioPHP\Core\GioPHPApp;

require 'Controllers/Home.php';

$app = new GioPHPApp();

$app->loader()->views = __DIR__."/Views";
$app->loader()->layout = __DIR__."/Template/_layout.php";
$app->loader()->connectionString = "sqlite:".__DIR__.'/database.db';

$app->router()->get('/public/', [Home::class, 'index']);
$app->router()->get('/public/404', [Home::class, 'notFound']);
$app->router()->get('/public/db', [Home::class, 'db']);
$app->router()->setNotFound('/public/404');

$app->router()->get('/public/param/:name', function($req, $res)
{
	var_dump($req->params);
});

$app->run();

?>