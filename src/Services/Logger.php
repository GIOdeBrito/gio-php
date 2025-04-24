<?php

namespace GioPHP\Services;

class Logger
{
	private string $outputDir;

	public function __construct (?string $outputPath = NULL)
	{
		$this->output = $outputPath ?? __DIR__;
	}

	public function setLogPath (string $path)
	{
		$this->output = $path;
	}

	private function log (string $level, string $message, array $context = [])
	{
		$date = date('Y-m-d H:m:s');

		$content = "[{$date}] [{$level}] {$message}";

		if(!empty($context))
		{
			$flattened = implode(' ', $context);
			$content .= " {$flattened}";
		}

		$content .= '\n';

		file_put_contents($this->output.'/log.txt', $content, FILE_APPEND | LOCK_EX);
	}

	public function info (string $message, array $context = [])
	{
		$this->log('INFO', $message, $context);
	}

	public function warning (string $message, array $context = [])
	{
		$this->log('WARNING', $message, $context);
	}
}

?>