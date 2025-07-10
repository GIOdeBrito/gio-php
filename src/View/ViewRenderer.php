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
		echo '<div data-name="gphpview-root"></div>';
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
		$parser = new DOMParser($this->htmlContent);

		$components = $this->components->getComponents();

		$customTags = array_keys($components);
		$nodes = $parser->getNodeTuple($customTags);

		//var_dump($customTags);

		foreach($nodes as $node)
		{
			$tagName = trim($node->localName);

			$replacement = NULL;

			if(!isset($components[$tagName]))
			{
				continue;
			}

			ob_start();
			call_user_func_array($components[$tagName], []);
			$element = ob_get_clean();

			$parser->replaceNode($node, $element);
		}

		//echo htmlspecialchars($parser->domToHTML());
		echo $parser->domToHTML();
	}
}

?>