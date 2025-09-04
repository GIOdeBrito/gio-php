<?php

namespace GioPHP\Routing;

use GioPHP\Http\{Request, Response};
use GioPHP\Services\{Loader, Logger, ComponentRegistry};
use GioPHP\Database\Db;
use GioPHP\Routing\ControllerRoute;

class Router
{
	private array $routes = [];
	private array $controllers = [];

	private string $notFoundPage = "";

	private Loader $loader;
	private Logger $logger;
	private Db $db;
	private ComponentRegistry $components;

	public function __construct (Loader $loader, Logger $logger, Db $db, ComponentRegistry $components)
	{
		$this->routes = [
			'GET' 		=> [],
			'POST' 		=> [],
			'PUT' 		=> [],
			'DELETE' 	=> []
		];

		$this->loader = $loader;
		$this->logger = $logger;
		$this->db = $db;
		$this->components = $components;
	}

	public function addController (string $controller): void
	{
		$reflect = new \ReflectionClass($controller);

		foreach($reflect->getMethods() as $method):

			$routeAttributes = $method->getAttributes();

			// Skip iteration if no attribute was found
			if(empty($routeAttributes))
			{
				continue;
			}

			foreach($routeAttributes as $attribute):

				$route = $attribute->newInstance();

				if(!$this->methodExists($route->method))
				{
					continue;
				}

				$controllerRoute = new ControllerRoute();
				$controllerRoute->method = $route->method;
				$controllerRoute->path = $route->path;
				$controllerRoute->description = $route->description;
				$controllerRoute->schema = $route->schema;
				$controllerRoute->controller = [$controller, $method->getName()];

				if($route->isError)
				{
					$this->notFoundPage = $controllerRoute->path;
				}

				$this->routes[$route->method][$route->path] = $controllerRoute;

			endforeach;

		endforeach;
	}

	public function call (): void
	{
		$req = new Request($this->logger);
		$res = new Response($this->loader, $this->logger, $this->components);

		$requestMethod = $req->method;

		if(!$this->methodExists($requestMethod))
		{
			$res->redirect("/");
		}

		$requestPath = $req->path;

		if(!array_key_exists($requestPath, $this->routes[$requestMethod]))
		{
			$res->redirect($this->notFoundPage);
		}

		$route = $this->routes[$requestMethod][$requestPath];

		$req->getSchema($route->schema);

		$controller = $this->controllerInstantiator($route->getController());
		$controller->{$route->getControllerMethod()}($req, $res);
	}

	// Checks whether a method exists in this router
	private function methodExists (string $method): bool
	{
		if(array_key_exists($method, $this->routes))
		{
			return true;
		}

		return false;
	}

	private function controllerInstantiator (string $className): object
	{
		$reflection = new \ReflectionClass($className);
		$constructor = $reflection->getConstructor();

		if(is_null($constructor))
		{
			return new $className();
		}

		// Available parameters for the controller's constructor
		$possibleParameters = [
			'database' 		=> $this->db,
			'logger' 		=> $this->logger,
			'components' 	=> $this->components
		];

		$controllerParams = [];

		foreach($constructor->getParameters() as $param):
			$paramName = $param->getName();

			if(!array_key_exists($paramName, $possibleParameters))
			{
				continue;
			}

			$controllerParams[$paramName] = $possibleParameters[$paramName];
		endforeach;

		return new $className(...$controllerParams);
	}
}

?>