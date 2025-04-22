<?php

use GioPHP\Core\Controller;

require __DIR__.'/../Models/Users.php';

class Home extends Controller
{
	public static function index ($req, $res): void
	{
		self::setTitle("Home");

		$res->setStatus(200);
		$res->render('Home', self::getViewData());
	}

	public static function db ($req, $res): void
	{
		self::setTitle("Db");

		$user = new USERS();

		$items = $user->select()->where('UNAME', 'GIORDANO')->and('ID', 1)->get();

		var_dump($items);
		die();

		$res->setStatus(200);
		$res->render('Home', self::getViewData());
	}

	public static function notFound ($req, $res)
	{
		echo "<h1>Not Found 404</h1>";

		$res->setStatus(404);
		$res->end();
	}
}

?>