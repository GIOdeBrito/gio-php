<?php

namespace GioPHP\Core;

ini_set('display_errors', '0');
error_reporting(E_ALL);

require_once __DIR__.'/../Error/ErrorHandling.php';
require_once __DIR__.'/../Helpers/DateTime.php';
require_once __DIR__.'/../Helpers/RouteAttributes.php';
require_once __DIR__.'/../Helpers/Types.php';

use GioPHP\Routing\Router;
use GioPHP\Services\{Loader, Logger, ComponentRegistry};
use GioPHP\Database\Db;

use function GioPHP\Error\{ErrorHandler, ShutdownHandler};

ErrorHandler();
ShutdownHandler();

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
		catch(\ErrorException $ex)
		{
			$this->logger->error($ex->getMessage());
			die();
		}
	}
}

?>