<?php

require __DIR__.'/../Models/Users.php';

use GioPHP\MVC\Controller;

//include constant('ABSPATH').'/Components/button-icon.php';

class Home extends Controller
{
	public function index ($req, $res): void
	{
		$viewData = [
			'title' => 'Home'
		];

		$res->setStatus(200);
		$res->render('Home', $viewData);
	}

	public static function staticTeste ($req, $res): void
	{
		$res->setStatus(200);
		$res->html('<h1>Teste Static</h1>');
	}

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
		$res->render('Home', $viewData);
	}

	public function notFound ($req, $res)
	{
		$res->setStatus(404);
		$res->html("<h1>Not Found<h1>");
	}
}

?>