<?php

namespace GioPHP\Core;

use GioPHP\Routing\Router;
use GioPHP\Services\Loader;
use GioPHP\Services\Logger;
use GioPHP\Abraxas\Db;

class GioPHPApp
{
	private ?Router $router = NULL;
	private ?Loader $loader = NULL;
	private ?Logger $logger = NULL;

	public function __construct ()
	{
		$this->logger = new Logger();
		$this->loader = new Loader();
		$this->router = new Router($this->loader, $this->logger);

		Db::constructor($this->loader);
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
		$this->logger->info("Application was started");

		$this->router->call();
		die();
	}
}

?>