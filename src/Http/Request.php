<?php

namespace GioPHP\Http;

require __DIR__.'/../Helpers/Types.php';

use GioPHP\Services\Logger;
use GioPHP\Http\FileData;
use function GioPHP\Helpers\convertToType;

class Request
{
	private string $method = "";
	private string $path = "";

	private array $form = [];
	private array $body = [];
	private array $query = [];
	private array $files = [];

	private Logger $logger;

	public function __construct (Logger $logger)
	{
		$this->method = mb_strtoupper($_SERVER["REQUEST_METHOD"]);
		$this->path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		$this->logger = $logger;
	}

	public function __get (string $name): mixed
	{
		if(!property_exists($this, $name))
		{
			$this->logger->error("Property '{$name}' does not have a getter function or does not exist.");
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

	private function form (): object
	{
		return (object) $this->form;
	}

	private function body (): object
	{
		return (object) $this->body;
	}

	private function query (): object
	{
		return (object) $this->query;
	}

	private function files (): object
	{
		return (object) $this->files;
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

				case 'query':
					$this->getQueryParam($key, $type);
					break;

				case 'file':
					$this->getFileParam($key, $type);
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

	private function getQueryParam (string $key, string $type): bool
	{
		if(!isset($_GET[$key]))
		{
			return false;
		}

		$value = convertToType($_GET[$key], $type);
		$this->query[$key] = $value;

		return true;
	}

	private function getFileParam (string $key, string $type): bool
	{
		if(!isset($_FILES[$key]))
		{
			return false;
		}

		$filedata = $_FILES[$key];

		// Checks if multiple files are being sent at once
		$isMultiForm = count($filedata['name']) > 0 ? true : false;

		if(!$isMultiForm)
		{
			$name = $filedata['name'];
			$fullpath = $filedata['full_path'];
			$type = $filedata['type'];
			$tempname = $filedata['tmp_name'];
			$error = $filedata['error'];
			$size = $filedata['size'];

			$this->files[$key] = new FileData(
				$name,
				$fullpath,
				$type,
				$tempname,
				$error,
				$size
			);

			return true;
		}

		for($i = 0; $i < count($filedata['name']); $i++):

			$name = $filedata['name'][$i];
			$fullpath = $filedata['full_path'][$i];
			$type = $filedata['type'][$i];
			$tempname = $filedata['tmp_name'][$i];
			$error = $filedata['error'][$i];
			$size = $filedata['size'][$i];

			$this->files[$key][$i] = new FileData(
				$name,
				$fullpath,
				$type,
				$tempname,
				$error,
				$size
			);

		endfor;

		return true;
	}
}

?>