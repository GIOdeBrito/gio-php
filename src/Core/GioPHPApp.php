<?php

namespace GioPHP\Core;

use GioPHP\Routing\Router;
use GioPHP\Services\Loader;
use GioPHP\Services\Logger;
use GioPHP\Abraxas\Db;

define("GIOPHP_SRC_ROOT_PATH", __DIR__.'/..');

class GioPHPApp
{
	private ?Router $router = NULL;
	private ?Loader $loader = NULL;
	private ?Logger $logger = NULL;
	private ?Db $db = NULL;

	public function __construct ()
	{
		$this->logger = new Logger();
		$this->loader = new Loader();

		$this->db = new Db($this->loader, $this->logger);
		$this->router = new Router($this->loader, $this->logger, $this->db);
	}

	public function logger (): object
	{
		return $this->logger;
	}

	public function router (): object
	{
		return $this->router;
	}

	public function loader (): object
	{
		return $this->loader;
	}

	public function run (): void
	{
		$this->router->call();
		die();
	}
}

?>