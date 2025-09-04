<?php

use GioPHP\MVC\Controller;
use GioPHP\Enums\ContentType;

use GioPHP\Attributes\Route;

class FileController extends Controller
{
	#[Route(
		method: 'GET',
		path: '/public/download',
		description: 'File download test.'
	)]
	public function fileDownload ($req, $res): void
	{
		$path = constant('ABSPATH').'/assets/hipopotamo.jpg';

		if(!file_exists($path))
		{
			$res->redirect('/public/404');
		}

		$res->setStatus(200);
		$res->file($path);
	}

	#[Route(
		method: 'GET',
		path: '/public/image',
		description: 'Image display test.'
	)]
	public function fileDisplay ($req, $res): void
	{
		$path = constant('ABSPATH').'/assets/hipopotamo.jpg';

		if(!file_exists($path))
		{
			$res->redirect('/public/404');
		}

		$res->setStatus(200);
		$res->file($path, ContentType::ImageJpg);
	}

	#[Route(
		method: 'GET',
		path: '/public/image64',
		description: 'Outputs image as base64.'
	)]
	public function fileBase64 ($req, $res): void
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