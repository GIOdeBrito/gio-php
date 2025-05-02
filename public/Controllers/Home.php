<?php

//use GioPHP\Core\Controller;

require __DIR__.'/../Models/Users.php';

use GioPHP\Abraxas\Db;

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

		Db::open();
		Db::exec("INSERT INTO USERS VALUES (:idd, :name, :num)", [ 'idd' => 2, 'name' => 'BRUNO', 'num' => 123 ]);
		Db::commit();
		Db::close();

		$user = new USERS();

		$items = $user->all()->toObject();

		foreach($items as $item)
		{
			echo $item->ID.PHP_EOL;
			echo $item->UNAME.PHP_EOL;
			echo $item->UPWD.PHP_EOL;
		}

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