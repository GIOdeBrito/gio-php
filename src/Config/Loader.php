<?php

namespace GioPHP\Config;

class Loader
{
	private string $views;
	private string $layout;

	public function __construct ()
	{
		$this->viewsPath = "App/Views";
		$this->layoutPath = "App/Template/_layout.php";
	}

	public function __set (string $key, mixed $param): void
	{
		if(!method_exists($this, $key))
		{
			return;
		}

		$this->{$key}($param);
	}

	public function __get (string $key): mixed
	{
		if(!property_exists($this, $key))
		{
			return NULL;
		}

		return $this->$key;
	}

	private function views (string $path): void
	{
		$this->views = $path;
	}

	private function layout (string $path): void
	{
		$this->layout = $path;
	}
}

?>