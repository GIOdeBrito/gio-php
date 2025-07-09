<?php

namespace GioPHP\DOM;

class DOMParser
{
	private object $objectModel;
	private string $htmlContent;

	public function __construct (string $html)
	{
		$this->htmlContent = $html;
		$this->objectModel = $this->stringToDOM($html);
	}

	private function stringToDOM (string $html): object
	{
		$document = new \DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML(mb_encode_numericentity($html, [0x80, 0x10FFFF, 0, ~0], 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        return $document;
	}

	public function save (): string
	{

	}
}

?>