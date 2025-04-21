<?php

namespace GioPHP\Http;

use GioPHP\Config\Loader;

enum ResponseTypeEnum
{
	case VIEW;
	case JSON;
	case PLAINTEXT;
	case HTML;
	case EMPTY;
}

class Response
{
	private int $status = 200;
	private string $contenttype = "";
	private mixed $body;
	private string $view;
	private array $viewparams = [];
	private ResponseTypeEnum $type;

	private Loader $loader;

	public function __construct (Loader $loader)
	{
		$this->loader = $loader;
	}

	public function render (string $view, array $params = []): void
	{
		$this->view = $view;
		$this->viewparams = $params;
		$this->contenttype = "text/html";
		$this->type = ResponseTypeEnum::VIEW;
		$this->send();
	}

	public function setStatus (int $code): void
	{
		$this->status = $code;
	}

	public function html (string $body): void
	{
		$this->body = $body;
		$this->contenttype = "text/html";
		$this->type = ResponseTypeEnum::HTML;
		$this->send();
	}

	public function json (array|object $data): void
	{
		$this->body = $data;
		$this->contenttype = "application/json";
		$this->type = ResponseTypeEnum::JSON;
		$this->send();
	}

	public function plain (string $body): void
	{
		$this->body = $body;
		$this->contenttype = "text/plain";
		$this->type = ResponseTypeEnum::PLAINTEXT;
		$this->send();
	}

	public function end (): void
	{
		$this->body = "";
		$this->contenttype = "text/html";
		$this->type = ResponseTypeEnum::EMPTY;
		$this->send();
	}

	public function redirect (string $url): void
	{
		header("Location: ${url}");
		die();
	}

	private function send (): void
	{
		http_response_code(intval($this->status));
		header('Content-Type: '.$this->contenttype);

		switch($this->type)
		{
			case ResponseTypeEnum::VIEW:
			{
				// Get the view's content
				ob_start();

				include $this->loader->views."/{$this->view}.php";

				$body = ob_get_clean();

				//die($this->loader->layout);

				// Extract the array key value pair as local variables
				extract($this->viewparams);

				// Load layout
				include $this->loader->layout;
			}
			break;
			case ResponseTypeEnum::JSON:
			{
				echo json_encode($this->body);
			}
			break;
			case ResponseTypeEnum::HTML:
			{
				include 'App/Views/'.$this->body.'.php';
			}
			break;
			case ResponseTypeEnum::PLAINTEXT:
			default:
			{
				echo $this->body;
			}
			break;
		}

		die();
	}
}

?>