<?php

define('ABSPATH', __DIR__);
date_default_timezone_set('America/Fortaleza');

require __DIR__.'/../vendor/autoload.php';

use GioPHP\Core\GioPHPApp as App;

require 'src/Controllers/Home.php';
require 'src/Controllers/FileController.php';

$app = new App();

$app->loader()->setViewDirectory(__DIR__."/src/Views");
$app->loader()->setConnectionString("sqlite:".__DIR__.'/database.db');

$app->router()->get('/public/', [Home::class, 'index']);
$app->router()->get('/public/db', [Home::class, 'db']);
$app->router()->get('/public/static', [Home::class, 'staticTeste']);

$app->router()->get('/public/404', [Home::class, 'notFound']);
$app->router()->set404('/public/404');

$app->router()->get('/public/download', [FileController::class, 'fileDownload']);
$app->router()->get('/public/image', [FileController::class, 'fileDisplay']);
$app->router()->get('/public/image64', [FileController::class, 'fileBase64']);

$app->components()->useComponents(true);
$app->components()->import(include constant('ABSPATH').'/src/Components/ButtonIcon/button-icon.php');

$app->run();

?>