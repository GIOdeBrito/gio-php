<?php

namespace GioPHP\Core;

use GioPHP\Routing\Router;
use GioPHP\Config\Loader;

class GioPHPApp
{
	private ?Router $router = NULL;
	private ?Loader $loader = NULL;

	public function __construct ()
	{
		$this->loader = new Loader();

		$this->router = new Router($this->loader);
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