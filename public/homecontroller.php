<?php

use GioPHP\Core\Controller;

class Home extends Controller
{
	public static function index ($req, $res): void
	{
		self::setTitle("Home");

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