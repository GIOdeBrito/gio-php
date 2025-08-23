<?php

namespace GioPHP\Http;

use GioPHP\Enums\{ResponseTypes, ContentTypes};

class ResponsePayload
{
	private int $status = 200;
	private ContentTypes $contenttype;
	private ResponseTypes $responsetype;
	private mixed $body = NULL;

	public function __get (string $key): mixed
	{
		$getter = 'get'.ucfirst($key);

		if(!method_exists($this, $getter))
		{
			throw new \OutOfBoundsException("Property '{$getter}' was not found on ResponsePayload.");
		}

		return $this->{$getter}();
	}

	public function __set (string $key, mixed $value): void
	{
		$setter = 'set'.ucfirst($key);

		if(!method_exists($this, $setter))
		{
			throw new \OutOfBoundsException("Could not set '{$setter}' on ResponsePayload.");
		}

		$this->{$setter}($value);
	}

	private function getStatus (): int
	{
		return $this->status;
	}

	private function getBody (): mixed
	{
		return $this->body;
	}

	private function getContentType (): ContentTypes
	{
		return $this->contenttype;
	}

	private function getResponseType (): ResponseTypes
	{
		return $this->responsetype;
	}

	private function setStatus (int $status): void
	{
		$this->status = intval($status);
	}

	private function setBody (mixed $value): void
	{
		$this->body = $value;
	}

	private function setContentType (ContentTypes $type): void
	{
		$this->contenttype = $type;
	}

	private function setResponseType (ResponseTypes $type): void
	{
		$this->responsetype = $type;
	}
}

?>