<?php

namespace GioPHP\Services;

class Loader
{
	private string $views = "";
	private string $layout;

	private string $connectionString;
	private ?string $dbLogin;
	private ?string $dbPwd;

	public function __construct ()
	{
		$this->layout = constant("SRC_ROOT_PATH")."/Template/_layout.php";

		$this->connectionString = "";
		$this->dbLogin = NULL;
		$this->dbPwd = NULL;
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
		$this->layout = $view.'/'.$path;
	}

	private function connectionString (string $connection): void
	{
		$this->connectionString = $connection;
	}
}

?>