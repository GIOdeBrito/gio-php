<?php

namespace GioPHP\Http;

use GioPHP\Services\Logger;

require __DIR__.'/../Helpers/Types.php';

use function GioPHP\Helpers\convertToType;

class Request
{
	private string $method = "";
	private string $path = "";
	private ?object $params = NULL;
	private array $form = [];
	private array $body = [];
	private ?object $files = NULL;

	private Logger $logger;

	public function __construct (Logger $logger)
	{
		$this->method = strtoupper($_SERVER["REQUEST_METHOD"]);
		$this->path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

		$this->logger = $logger;
	}

	public function __get (string $name): mixed
	{
		if(!property_exists($this, $name))
		{
			//throw new Exception("Property {$name} does not have a getter function or does not exist");
			$this->logger->error("Property {$name} does not have a getter function or does not exist.");
			return NULL;
		}

		return $this->{$name}();
	}

	private function method (): string
	{
		return strtoupper($this->method);
	}

	private function path (): string
	{
		return $this->path;
	}

	private function params (): object
	{
		return $this->params;
	}

	private function form (): object
	{
		return (object) ($this->form ?? []);
	}

	private function body (): object
	{
		return (object) $this->body;
	}

	private function file (): object
	{
		return $this->files;
	}

	private function getPosted (): void
	{
		// Gets formdata
		$this->form = (object) (json_decode($_POST ?? '', true));

		// Gets JSON
		$this->body = (object) (json_decode(file_get_contents('php://input') ?? '', true));

		// Gets files via formdata
		$this->files = (object) ($_FILES ?? []);
	}

	public function getSchema (array $schema = []): void
	{
		foreach($schema as $key => $value):

			$name = $key;
			$entry = mb_strtolower(explode(':', $value)[0], 'UTF-8');
			$type = mb_strtolower(explode(':', $value)[1], 'UTF-8');

			switch($entry)
			{
				case 'form':
					$this->getFormParam($key, $type);
					break;

				case 'json':
					$this->getJsonParam($key, $type);
					break;

				default:
					break;
			}

		endforeach;
	}

	private function getFormParam (string $key, string $type): bool
	{
		if(!isset($_POST[$key]))
		{
			return false;
		}

		$value = convertToType($_POST[$key], $type);
		$this->form[$key] = $value;

		return true;
	}

	private function getJsonParam (string $key, string $type): bool
	{
		$json = json_decode(file_get_contents('php://input') ?? '', true);

		if(!isset($json[$key]))
		{
			return false;
		}

		$value = convertToType($json[$key], $type);
		$this->body[$key] = $value;

		return true;
	}
}

?>