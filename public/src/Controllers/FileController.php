<?php

use GioPHP\MVC\Controller;
use GioPHP\Enums\ContentType;

class FileController extends Controller
{
	public static function fileDownload ($req, $res): void
	{
		$path = constant('ABSPATH').'/assets/hipopotamo.jpg';

		if(!file_exists($path))
		{
			$res->redirect('/public/404');
		}

		$res->setStatus(200);
		$res->file($path);
	}

	public static function fileDisplay ($req, $res): void
	{
		$path = constant('ABSPATH').'/assets/hipopotamo.jpg';

		if(!file_exists($path))
		{
			$res->redirect('/public/404');
		}

		$res->setStatus(200);
		$res->file($path, ContentType::ImageJpg);
	}

	public static function fileBase64 ($req, $res): void
	{
		$path = constant('ABSPATH').'/assets/hipopotamo.jpg';

		if(!file_exists($path))
		{
			$res->redirect('/public/404');
		}

		$content = base64_encode(file_get_contents($path));

		$res->setStatus(200);
		$res->plain($content);
	}
}

?>