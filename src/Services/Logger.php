<?php

namespace GioPHP\Services;

class Logger
{
	private ?string $outputDir;

	public function __construct (?string $outputPath = NULL)
	{
		$this->outputDir = $outputPath;
	}

	public function setLogPath (string $path): void
	{
		$this->outputDir = $path;
	}

	private function log (string $level, string $message, array $context = []): void
	{
		if(is_null($this->outputDir))
		{
			return;
		}

		$date = date('Y-m-d H:m:s');

		$content = "[{$date}] [{$level}] {$message}";

		if(!empty($context))
		{
			$flattened = implode(' ', $context);
			$content .= " {$flattened}";
		}

		$content .= PHP_EOL;

		file_put_contents($this->outputDir.'/log.txt', $content, FILE_APPEND | LOCK_EX);
	}

	public function info (string $message, array $context = []): void
	{
		$this->log('INFO', $message, $context);
	}

	public function warning (string $message, array $context = []): void
	{
		$this->log('WARNING', $message, $context);
	}

	public function error (string $message, array $context = []): void
	{
		$this->log('ERROR', $message, $context);
	}
}

?>