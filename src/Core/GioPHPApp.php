<?php

namespace GioPHP\Core;

use GioPHP\Routing\Router;
use GioPHP\Services\{Loader, Logger, ComponentRegistry};
use GioPHP\Database\Db;

define("GIOPHP_SRC_ROOT_PATH", __DIR__.'/..');

class GioPHPApp
{
	private ?Router $router = NULL;
	private ?Loader $loader = NULL;
	private ?Logger $logger = NULL;
	private ?ComponentRegistry $components = NULL;
	private ?Db $db = NULL;

	public function __construct ()
	{
		$this->logger = new Logger();
		$this->loader = new Loader();

		$this->components = new ComponentRegistry($this->logger);
		$this->db = new Db($this->loader, $this->logger);
		$this->router = new Router($this->loader, $this->logger, $this->db, $this->components);
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

	public function components (): object
	{
		return $this->components;
	}

	public function run (): void
	{
		try
		{
			$response = $this->router->call();
			die();
		}
		catch(\Exception $ex)
		{
			$this->logger->error($ex->getMessage());
			die();
		}
	}
}

?>