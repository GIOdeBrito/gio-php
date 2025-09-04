<?php

namespace GioPHP\Http;

class FileData
{
	private string $name;
	private string $fullpath;
	private string $type;
	private string $tempname;
	private int $error;
	private int $size;

	public function __construct (string $name, string $fullpath, string $type, string $tempname, int $error, int $size)
	{
		$this->name = $name;
		$this->fullpath = $fullpath;
		$this->type = $type;
		$this->tempname = $tempname;
		$this->error = $error;
		$this->size = $size;
	}

	public function name (): string
	{
		return trim($this->name);
	}

	public function fullPath (): string
	{
		return trim($this->fullpath);
	}

	public function contentType (): string
	{
		return trim($this->type);
	}

	public function tempName (): string
	{
		return trim($this->tempname);
	}

	public function error (): int
	{
		return $this->error;
	}

	public function size (): int
	{
		return $this->size;
	}

	public function inKiloBytes (): float
	{
		return $this->size / 1000 ;
	}

	public function inMegaBytes (): float
	{
		return ($this->size / 1000) / 1000;
	}

	public function extension (): string
	{
		return pathinfo($this->name, PATHINFO_EXTENSION);
	}
}

?>