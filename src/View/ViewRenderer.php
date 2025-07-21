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

		//var_dump($components);
		//die();

		$customTags = array_keys($components);
		$nodes = $parser->getNodeTuple($customTags);

		//$tags = $parser?->getTagNames();

		foreach($nodes as $node)
		{
			$tagName = trim($node->localName);

			if(!isset($components[$tagName]))
			{
				continue;
			}

			// Store the component's content into the buffer
			$element = $this->createElement($node, $components[$tagName]);

			$parser->replaceNode($node, $element);
		}

		//echo htmlspecialchars($parser->domToHTML());
		//echo $parser->domToHTML();

		$this->htmlContent = $parser->domToHTML();
	}

	public function createElement ($node, $componentCallback)
	{
		$attr = DOMParser::getNodeAttributes($node, 'g:');

		$attrKvp = [];

		array_walk($attr->attribute, function ($value, $key) use (&$attrKvp)
		{
			array_push($attrKvp, "{$key}=\"{$value}\"");
		});

		$attributes = implode(' ', $attrKvp);
		$value = DOMParser::getNodeInnerText($node);
		$custom = $attr->custom ?? [];

		// Create element in a limited context
		return (function() use ($componentCallback, $value, $custom, $attributes)
		{
			$args = [ 'value' => $value, ...$custom, 'attributes' => $attributes ];

			ob_start();
			call_user_func_array($componentCallback, $args);
			return ob_get_clean();
		})();
	}

	public function getHtml (): string
	{
		return $this->htmlContent;
	}
}

?>