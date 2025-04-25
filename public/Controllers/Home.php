<?php

//use GioPHP\Core\Controller;

require __DIR__.'/../Models/Users.php';

class Home
{
	public static function index ($req, $res): void
	{
		$viewData = [
			'title' => 'Home'
		];

		$res->setStatus(200);
		$res->render('Home', $viewData);
	}

	public static function db ($req, $res): void
	{
		$viewData = [
			'title' => 'Db'
		];

		$user = new USERS();

		$items = $user->select()->where('UNAME', 'GIORDANO')->and('ID', 1)->asc()->object();

		var_dump($items);

		//echo $user->select()->where('UNAME', 'GIORDANO')->and('ID', 1)->asc()->sql();
		die();

		$res->setStatus(200);
		$res->render('Home', $viewData);
	}

	public static function notFound ($req, $res)
	{
		$res->setStatus(404);
		$res->html("<h1>Not Found<h1>");
	}
}

?>