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

$app->router()->addController(Home::class);
$app->router()->addController(FileController::class);

$app->components()->useComponents(true);
$app->components()->import(include constant('ABSPATH').'/src/Components/ButtonIcon/button-icon.php');

$app->run();

?>