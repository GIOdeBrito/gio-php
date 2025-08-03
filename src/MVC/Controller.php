<?php

namespace GioPHP\MVC;

use GioPHP\Database\Db as Database;
use GioPHP\Services\{Logger, ComponentRegistry};

abstract class Controller
{
	protected Database $database;
	protected Logger $logger;
	protected ComponentRegistry $components;

	public function __construct (Database $database, Logger $logger, ComponentRegistry $components)
	{
		$this->database = $database;
		$this->logger = $logger;
		$this->components = $components;
	}

	protected function getDatabase (): object
	{
		return $this->database;
	}

	protected function getLogger (): object
	{
		return $this->logger;
	}

	protected function getComponents (): object
	{
		return $this->components;
	}
}

?>