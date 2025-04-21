<?php

require __DIR__.'/../vendor/autoload.php';

use GioPHP\Core\GioPHPApp;

require 'homecontroller.php';

$app = new GioPHPApp();
$app->router()->setNotFound('/public/404');

$app->loader()->views = __DIR__."/Views";
$app->loader()->layout = __DIR__."/_layout.php";

$app->router()->get('/public/', [Home::class, 'index']);
$app->router()->get('/public/404', [Home::class, 'notFound']);

$app->run();

?>