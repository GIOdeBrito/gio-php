<?php

require constant('ABSPATH').'/src/Models/Users.php';

use GioPHP\MVC\Controller;
use GioPHP\Routing\Route;

class Home extends Controller
{
	#[Route(
		method: 'GET',
		path: '/public/',
		description: 'Home page.'
	)]
	public function index ($req, $res): void
	{
		$viewData = [
			'title' => 'Home'
		];

		$res->setStatus(200);
		$res->render('Home', '_layout', $viewData);
	}

	#[Route(
		method: 'GET',
		path: '/public/database',
		description: 'Database test page.'
	)]
	public function db ($req, $res): void
	{
		$viewData = [
			'title' => 'Db'
		];

		$db = $this->getDatabase();
		$db->open();
		//$db->exec("INSERT INTO USERS VALUES (:idd, :name, :num)", [ 'idd' => 2, 'name' => 'BRUNO', 'num' => 123 ]);
		$res = $db->query("SELECT * FROM USERS");

		var_dump($res);
		die();

		$res->setStatus(200);
		$res->render('Home', '_layout', $viewData);
	}

	#[Route(
		method: 'POST',
		path: '/public/schema',
		description: 'Schema test page.',
		schema: [ 'id' => 'json:int', 'name' => 'json:string' ]
	)]
	public function schema ($req, $res): void
	{
		var_dump($req->body);
		$res->end(200);
	}

	#[Route(
		method: 'GET',
		path: '/public/error',
		description: 'Default error page.',
		isError: true
	)]
	public function notFound ($req, $res)
	{
		$res->setStatus(404);
		$res->html("<h1>Not Found<h1>");
	}
}

?>