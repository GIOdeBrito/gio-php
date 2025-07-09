<?php

/* View renderer */

namespace GioPHP\View;

use GioPHP\DOM\DOMParser;
use GioPHP\Services\ComponentRegistry;

class ViewRenderer
{
	private string $htmlContent = '';
	private object $dom;

	private ComponentRegistry $components;

	public function __construct (ComponentRegistry $components)
	{
		$this->components = $components;
	}

	public function beginCapture (): void
	{
		ob_start();
		echo '<div data-name="pseudo-view-root"></div>';
	}

	public function endCapture (): void
	{
		$this->htmlContent = ob_get_clean();
	}

	public function setComponentsForElements (): void
	{
		if(empty($this->htmlContent))
		{
			return;
		}

		$this->parseHTML();
	}

	private function parseHTML (): void
	{
		$this->dom = new DOMParser($this->htmlContent);
		$dom = $this->dom;

		foreach(array_keys($this->components->getComponents()) as $tagName)
		{
			echo "tag".$tagName;
		}
	}
}

?>