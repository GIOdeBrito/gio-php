<?php

namespace GioPHP\Http;

use GioPHP\Enums\{ResponseTypes, ContentType};
use GioPHP\Services\{Loader, Logger, ComponentRegistry};
use GioPHP\Http\ResponsePayload;
use GioPHP\View\ViewRenderer;

class Response
{
	private string $view;
	private string $layout;
	private array $viewparams = [];

	private ResponsePayload $payload;

	private Loader $loader;
	private Logger $logger;
	private ComponentRegistry $components;

	public function __construct (Loader $loader, Logger $logger, ComponentRegistry $components)
	{
		$this->loader = $loader;
		$this->logger = $logger;
		$this->components = $components;

		$this->payload = new ResponsePayload();
	}

	private function setPayload (mixed $body, ResponseTypes $response, string $content, int $status = 200)
	{
		$this->payload->body = $body;
		$this->payload->contentType = $content;
		$this->payload->responseType = $response;
		$this->payload->status = $status;
	}

	public function render (string $view, string $layout, array $params = []): void
	{
		$this->view = $view;
		$this->layout = $layout;
		$this->viewparams = $params;

		$this->setPayload('', ResponseTypes::VIEW, ContentType::Html);
		$this->send();
	}

	public function setStatus (int $code): void
	{
		$this->payload->status = $code;
	}

	public function html (string $body): void
	{
		$this->setPayload($body, ResponseTypes::HTML, ContentType::Html);
		$this->send();
	}

	public function json (array|object $data): void
	{
		$this->setPayload($data, ResponseTypes::JSON, ContentType::Json);
		$this->send();
	}

	public function plain (string $body): void
	{
		$this->setPayload($body, ResponseTypes::PLAINTEXT, ContentType::PlainText);
		$this->send();
	}

	public function file (string $path, string $type = ContentType::FileStream): void
	{
		$this->setPayload($path, ResponseTypes::FILE, $type);
		$this->send();
	}

	public function end (): void
	{
		$this->setPayload('', ResponseTypes::PLAINTEXT, ContentType::PlainText);
		$this->send();
	}

	public function redirect (string $url): void
	{
		http_response_code(301);
		header("Location: {$url}");
		die();
	}

	private function send (): void
	{
		http_response_code(intval($this->payload->status));
		header('Content-Type: '.$this->payload->contentType);

		try
		{
			switch($this->payload->responseType)
			{
				case ResponseTypes::VIEW:
					$this->sendView();
					break;
				case ResponseTypes::JSON:
					$this->sendJson();
					break;
				case ResponseTypes::HTML:
					$this->sendHtml();
					break;
				case ResponseTypes::FILE:
					$this->sendFile();
					break;
				case ResponseTypes::PLAINTEXT:
					$this->sendPlain();
					break;
				default:
					throw new \LogicException("Unknown response '{$this->payload->responseType}'.");
			}
		}
		catch(\Exception $ex)
		{
			$this->logger?->error($ex?->getMessage());
			http_response_code(500);
			echo "Internal Server Error";
		}

		die();
	}

	private function sendView (): void
	{
		$viewPath = $this->loader->getViewDirectory();

		if(empty($viewPath))
		{
			throw new \Exception("Views path was not set.");
		}

		$viewrenderer = new ViewRenderer($this->components);
		$viewFilePath = "{$viewPath}/{$this->view}.php";

		if(!file_exists($viewFilePath))
		{
			throw new \Exception("Could not find view file.");
		}

		// Capture view's content
		$viewrenderer->beginCapture();
		include $viewFilePath;
		$viewrenderer->endCapture();

		// Replace components if allowed
		if($this->components->isUsingComponents())
		{
			$viewrenderer->setComponentsForElements();
		}

		$body = $viewrenderer->getHtml();

		// Extract params as proper variables
		extract($this->viewparams);

		// Load layout
		include "{$this->loader?->getLayoutDirectory()}/{$this->layout}.php";
	}

	private function sendJson (): void
	{
		echo json_encode($this->payload->body ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	private function sendHtml (): void
	{
		echo $this->payload->body;
	}

	private function sendFile (): void
	{
		readfile($this->payload->body);
	}

	private function sendPlain (): void
	{
		echo $this->payload->body ?? "";
	}
}

?>