<?php

namespace GioPHP\Http;

use GioPHP\Enums\{ResponseTypes, ContentTypes};
use GioPHP\Services\{Loader, Logger, ComponentRegistry};
use GioPHP\View\ViewRenderer;

class Response
{
	private int $status = 200;
	private ContentTypes $contenttype;
	private mixed $body;
	private string $view;
	private array $viewparams = [];

	private Loader $loader;
	private Logger $logger;
	private ComponentRegistry $components;

	public function __construct (Loader $loader, Logger $logger, ComponentRegistry $components)
	{
		$this->loader = $loader;
		$this->logger = $logger;
		$this->components = $components;
	}

	public function render (string $view, array $params = []): void
	{
		$this->view = $view;
		$this->viewparams = $params;
		$this->contenttype = ContentTypes::HTML;
		$this->type = ResponseTypes::VIEW;
		$this->send();
	}

	public function setStatus (int $code): void
	{
		$this->status = $code;
	}

	public function html (string $body): void
	{
		$this->body = $body;
		$this->contenttype = ContentTypes::HTML;
		$this->type = ResponseTypes::HTML;
		$this->send();
	}

	public function json (array|object $data): void
	{
		$this->body = $data;
		$this->contenttype = ContentTypes::JSON;
		$this->type = ResponseTypes::JSON;
		$this->send();
	}

	public function plain (string $body): void
	{
		$this->body = $body;
		$this->contenttype = ContentTypes::PLAIN;
		$this->type = ResponseTypes::PLAINTEXT;
		$this->send();
	}

	public function file (string $path): void
	{
		$this->body = $path;
		$this->contenttype = ContentTypes::FILE;
		$this->send();
	}

	public function end (): void
	{
		$this->contenttype = ContentTypes::PLAIN;
		$this->send();
	}

	public function redirect (string $url): void
	{
		http_response_code(301);
		header("Location: ${url}");
		die();
	}

	private function send (): void
	{
		http_response_code(intval($this->status));
		header('Content-Type: '.$this->contenttype->value);

		switch($this->type)
		{
			case ResponseTypes::VIEW: 		$this->sendView(); break;
			case ResponseTypes::JSON: 		$this->sendJson(); break;
			case ResponseTypes::HTML: 		$this->sendHtml(); break;
			case ResponseTypes::FILE: 		$this->sendFile(); break;
			case ResponseTypes::PLAINTEXT: 	$this->sendPlain(); break;
		}

		die();
	}

	private function sendView (): void
	{
		try
		{
			$viewPath = $this->loader->views;

			if(empty($viewPath))
			{
				throw new \Exception("Views path was not set.");
			}

			$viewrenderer = new ViewRenderer($this->components);
			$viewrenderer->beginCapture();

			// Get the view's content
			//ob_start();

			include $viewPath."/{$this->view}.php";

			//$body = ob_get_clean();
			$viewrenderer->endCapture();
			$viewrenderer->setComponentsForElements();

			// Extract the array key value pair as local variables
			extract($this->viewparams);

			// Load layout
			include $this->loader->layout;
		}
		catch(\Exception $ex)
		{
			$this->logger->error($ex->getMessage());
		}
	}

	private function sendJson (): void
	{
		echo json_encode($this->body ?? []);
	}

	private function sendHtml (): void
	{
		try
		{
			//include "{$this->loader->views}/".$this->body.".php";
			echo $this->body;
		}
		catch(\Exception $ex)
		{
			$this->logger->error($ex->getMessage());
		}
	}

	private function sendFile (): void
	{
		try
		{
			readfile($this->body);
		}
		catch(\Exception $ex)
		{
			$this->logger->error($ex->getMessage());
		}
	}

	private function sendPlain (): void
	{
		echo $this->body ?? "";
	}
}

?>