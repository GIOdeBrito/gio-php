<?php

namespace GioPHP\Http;

class Request
{
	private string $method = "";
	private string $uri = "";
	private ?object $params = NULL;
	private ?object $form = NULL;
	private ?object $body = NULL;
	private ?object $files = NULL;

	public function __construct ()
	{
		$this->method = strtoupper($_SERVER["REQUEST_METHOD"]);
		$this->uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

		$this->getPosted();
	}

	public function __get (string $name): mixed
	{
		if(!property_exists($this, $name))
		{
			throw new Exception("Property {$name} does not have a getter function or does not exist");
		}

		return $this->{$name}();
	}

	private function method (): string
	{
		return strtoupper($this->method);
	}

	private function uri (): string
	{
		return $this->uri;
	}

	private function params (): object
	{
		return $this->params;
	}

	private function form (): object
	{
		return $this->form;
	}

	private function body (): object
	{
		return $this->body;
	}

	private function file (): object
	{
		return $this->files;
	}

	private function getPosted (): void
	{
		$this->form = (object) (json_decode($_POST['body'] ?? '', true));

		$this->body = (object) (json_decode(file_get_contents('php://input') ?? '', true));

		$this->files = (object) ($_FILES['uploadedfiles'] ?? []);
	}

	// Checks whether the two routes are the same, also extracts the route parameters
	public function parseRoute (string $route): bool
	{
		$server_uri_array = explode('/', $route);
		$req_uri_array = explode('/', $this->uri);

		if(count($server_uri_array) !== count($req_uri_array))
		{
			return false;
		}

		$params = [];

		foreach($server_uri_array as $i => $value)
		{
			if(str_starts_with($value, ':'))
			{
				$params[substr($value, 1)] = $req_uri_array[$i];
			}
			else if($value !== $req_uri_array[$i])
			{
				return false;
			}
		}

		$this->params = (object) $params;

		return true;
	}
}

?>