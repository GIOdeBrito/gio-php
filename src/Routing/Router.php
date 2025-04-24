<?php

namespace GioPHP\Routing;

use GioPHP\Http\Request;
use GioPHP\Http\Response;
use GioPHP\Services\Loader;
use GioPHP\Services\Logger;

class Router
{
	private ?array $routes = NULL;
	private string $notFoundPage = "";

	private Loader $loader;
	private Logger $logger;

	public function __construct (Loader $loader, Logger $logger)
	{
		$this->routes = [
			'GET' 		=> [],
			'POST' 		=> [],
			'PUT' 		=> [],
			'DELETE' 	=> []
		];

		$this->notFoundPage = "/404";

		$this->loader = $loader;
		$this->logger = $logger;
	}

	public function get (string $route, object|string|array $callback)
	{
		$this->addRoute('GET', $route, $callback);
	}

	public function post (string $route, object|string|array $callback)
	{
		$this->addRoute('POST', $route, $callback);
	}

	public function put (string $route, object|string|array $callback)
	{
		$this->addRoute('PUT', $route, $callback);
	}

	public function delete (string $route, object|string|array $callback)
	{
		$this->addRoute('DELETE', $route, $callback);
	}

	private function addRoute (string $method, string $route, object|string|array $callback): void
	{
		if(!array_key_exists($method, $this->routes))
		{
			http_response_code(500);
			echo 'Method not set';
			die();
		}

		if(!is_callable($callback))
		{
			http_response_code(500);
			echo 'Callback function not set';
			die();
		}

		$this->routes[$method][$route] = $callback;
	}

	public function setNotFound ($address)
	{
		$this->notFoundPage = $address;
	}

	public function call (): void
	{
		$req = new Request();
		$res = new Response($this->loader);

		// Checks if the request method does exist in the router
		if(!array_key_exists($req->method, $this->routes))
		{
			$res->redirect("/");
		}

		$route = NULL;

		// Looks for the registered route
		foreach($this->routes[$req->method] as $key => $value)
		{
			if(!$req->parseRoute($key, $req->uri))
			{
				continue;
			}

			$route = $key;
		}

		// If route does not exists, redirects user to the 404 page
		if(is_null($route))
		{
			$this->logger->warning("Route {$req->uri} not found");
			$res->redirect($this->notFoundPage);
		}

		$this->logger->info("Route {$req->uri} found");

		$this->routes[$req->method][$route]($req, $res);
	}
}

?>