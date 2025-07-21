<?php

define('ABSPATH', __DIR__);

require __DIR__.'/../vendor/autoload.php';

use GioPHP\Core\GioPHPApp as App;

require 'Controllers/Home.php';

$app = new App();

# // TODO: Switch from named functions to a generic set. Ex: ->set('views', path)

$app->loader()->views = __DIR__."/Views";
//$app->loader()->layout = __DIR__."/Template/_layout.php";
$app->loader()->connectionString = "sqlite:".__DIR__.'/database.db';

$app->router()->get('/public/', [Home::class, 'index']);
$app->router()->get('/public/404', [Home::class, 'notFound']);
$app->router()->get('/public/db', [Home::class, 'db']);
$app->router()->get('/public/static', [Home::class, 'staticTeste']);

$app->router()->get('/public/param/:name', function($req)
{
	var_dump($req->params);
});

$app->router()->set404('/public/404');

$app->components()->useComponents(true);

require constant('ABSPATH').'/Components/button-icon.php';

$app->run();

?>