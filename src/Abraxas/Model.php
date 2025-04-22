<?php

namespace GioPHP\Abraxas;

use GioPHP\Abraxas\QueryBuilder;

class Model
{
	private array $params;
	private string $table;

	public function __construct ()
	{
		$this->table = get_class($this);
	}

	public function query (): object|null
	{
		return new QueryBuilder($this->table);
	}
}

?>