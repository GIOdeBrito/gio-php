<?php

namespace GioPHP\DOM;

class DOMParser
{
	private object $DOM;
	private string $htmlContent;

	public function __construct (string $html)
	{
		$this->htmlContent = $html;
		$this->DOM = $this->stringToDOMDocument($html);
	}

	private function stringToDOMDocument (string $html): object
	{
		$document = new \DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML(mb_encode_numericentity($html, [0x80, 0x10FFFF, 0, ~0], 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        return $document;
	}

	public function stringToNode (string $html): object
	{
		$dom = $this->stringToDOMDocument($html);

		return $dom->importNode($dom->firstChild, true);
	}

	public function domNodeOuterHtml (object $node): string
	{
		if(is_null($node->ownerDocument))
		{
			return $node->firstChild->ownerDocument->saveXML($node->firstChild);
		}

		return $node->ownerDocument->saveXML($node);
	}

	public function domNodeInnerHtml (object $node): string
	{
		return implode(' ', array_map(fn($child) => $this->domNodeOuterHtml($child), iterator_to_array($node->firstChild->childNodes)));

		/*return;
		$html = [];

		foreach($node->childNodes as $child)
		{
			array_push($html, $this->domNodeOuterHtml($child));
		}

		return implode(' ', $html);*/
	}

	public function getNodeTuple (array $tags = []): array
	{
		if(empty($tags))
		{
			return [];
		}

		$document = $this->DOM;
		$nodeLists = array_map(fn($tagName) => iterator_to_array($document->getElementsByTagName($tagName)), $tags);

		$nodeTuple = [];

		foreach($nodeLists as $list)
		{
			foreach ($list as $item)
			{
				array_push($nodeTuple, $item);
			}
		}

		return $nodeTuple;
	}

	public function replaceNode (object $node, object|string $replacement)
	{
		$newNode = $replacement;

		if(gettype($replacement) === "string")
		{
			$newNode = $this->stringToNode($replacement);
		}

		// Import new node into the DOM
		$newNode = $this->DOM->importNode($newNode, true);

		// Replace the old node
		$node->parentNode->replaceChild($newNode, $node);
	}

	public function domToHTML (): string
	{
		// Returns the view content without the root div
		return $this->domNodeInnerHtml($this->DOM);
	}
}

?>