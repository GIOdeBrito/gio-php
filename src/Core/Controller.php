<?php

namespace GioPHP\Core;

use GioPHP\Http\Request;
use GioPHP\Http\Response;

abstract class Controller
{
	protected static array $data = [
		'title' => 'View',
		'scripts' => [],
		'styles' => []
	];

	/* Must be implemented by child class */
	abstract public static function index (Request $req, Response $res): void;

	protected static function getViewData (): array
	{
		return self::$data;
	}

	protected static function setTitle (string $name): void
	{
		self::$data['title'] = $name;
	}

	protected static function enqueueScript (string $file, string $version = "1.0.0", bool $ismodule = false): void
	{
		$type = "text/javascript";

		if($ismodule)
		{
			$type = "module";
		}

		self::$data['scripts'][] = [ 'file' => $file, 'type' => $type, 'v' => $version ];
	}

	protected static function enqueueStyle (string $file, string $version = "1.0.0"): void
	{
		self::$data['styles'][] = [ 'file' => $file, 'v' => $version ];
	}
}

?>