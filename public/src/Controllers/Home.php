<?php

require constant('ABSPATH').'/src/Models/Users.php';

use GioPHP\MVC\Controller;
use GioPHP\Attributes\Route;

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
		method: 'GET',
		path: '/public/upload',
		description: 'File upload page.'
	)]
	public function indexUpload ($req, $res): void
	{
		$res->setStatus(200);
		$res->render('FileUpload', '_layout');
	}

	#[Route(
		method: 'POST',
		path: '/public/schema',
		description: 'Schema JSON test page.',
		schema: [ 'id' => 'json:int', 'name' => 'json:string' ]
	)]
	public function schema ($req, $res): void
	{
		var_dump($req->body);
		$res->end(200);
	}

	#[Route(
		method: 'GET',
		path: '/public/query',
		description: 'Schema query test page.',
		schema: [ 'id' => 'query:int', 'name' => 'query:string' ]
	)]
	public function schemaQuery ($req, $res): void
	{
		$res->html("
			<h1>ID: {$req->query->id}</h1>
			<h1>Name: {$req->query->name}</h1>
		");
	}

	#[Route(
		method: 'POST',
		path: '/public/fileschema',
		description: 'Schema file upload endpoint.',
		schema: [ 'annex' => 'file:jpg|jpeg|png[]' ]
	)]
	public function schemaFile ($req, $res): void
	{
		$files = $req->files->annex;

		if(!is_array($files))
		{
			$res->html("
				<h1>Not an array!</h1>
			");
		}

		foreach($files as $item)
		{
			?>
			<p>Name: <?= $item->name() ?></p>
			<p>Extension: <?= $item->extension() ?></p>
			<p>Size: <?= $item->inKiloBytes() ?>KB</p>
			<br>
			<?php
		}

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