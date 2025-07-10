<?php

namespace GioPHP\Services;

class ComponentRegistry
{
	private bool $useComponents = false;
	private array $registeredComponents = [];

	public function useComponents ($value): void
	{
		$this->useComponents = $value;
	}

	public function isUsingComponents (): bool
	{
		return $this->useComponents;
	}

	public function register (string $tagName, string|array|object $callback)
	{
		// Checks if the tag already exists or if the function is callable
		if(array_key_exists($tagName, $this->registeredComponents) || !is_callable($callback))
        {
			return;
        }

		$this->registeredComponents[$tagName] = $callback;
	}

	public function getComponents (): array
	{
		return $this->registeredComponents;
	}
}

?>