<?php

namespace GioPHP\DOM;

class Component
{
	private string $tag;
	private mixed $template;
	private array $params;

	public function __construct (string $tag, mixed $template, array $params = NULL)
	{
		$this->tag = $tag;
		$this->template = $template;
		$this->params = $params ?? [];
	}

	public function render ($attrs = []): void
	{
		var_dump($attrs);

		if(gettype($this->template) === 'string')
		{
			include $this->template;
		}
	}

	public function getTagName (): string
	{
		return $this->tag;
	}
}

?>