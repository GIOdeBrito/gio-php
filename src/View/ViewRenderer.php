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
		echo '<div id="gphpview-root"></div>';
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

		$customTags = array_keys($this->components->getComponents());
		$nodes = $parser->getNodeTuple($customTags);

		//var_dump($nodes);

		foreach($nodes as $nodeList)
		{
var_dump($nodeList);

			foreach($nodeList as $node) {
				$parser->replaceNode($node, '');
			}
		}

		echo htmlspecialchars($parser->domToHTML());
	}
}

?>