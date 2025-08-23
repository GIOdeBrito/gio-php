<?php

namespace GioPHP\Services;

class Loader
{
	private string $views = "";
	private string $layout = "";

	private string $connectionString;
	private ?string $dbLogin;
	private ?string $dbPwd;

	public function __construct ()
	{
		$this->layout = constant("GIOPHP_SRC_ROOT_PATH")."/Template/";

		$this->connectionString = "";
		$this->dbLogin = NULL;
		$this->dbPwd = NULL;
	}

	public function setViewDirectory (string $path): void
	{
		$this->views = $path;
	}

	public function setLayoutDirectory (string $path): void
	{
		$this->layout = $path;
	}

	public function setConnectionString (string $connection): void
	{
		$this->connectionString = $connection;
	}

	public function getViewDirectory (): string
	{
		return $this->views;
	}

	public function getLayoutDirectory (): string
	{
		return $this->layout;
	}

	public function getConnectionString (): string
	{
		return $this->connectionString;
	}

	public function getDatabaseLogin (): string
	{
		return $this->dbLogin ?? '';
	}

	public function getDatabaseSecret (): string
	{
		return $this->dbPwd ?? '';
	}
}

?>