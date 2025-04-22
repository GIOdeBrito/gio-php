<?php

namespace GioPHP\Abraxas;

use GioPHP\Abraxas\Db;

class QueryBuilder
{
	private string $cmd;
	private string $table;

	public function __construct (string $table)
	{
		if(!Db::open())
		{
			echo "Could not open DB connection";
		}

		$this->cmd = "";
		$this->table = "";
	}

	public function __destruct ()
	{
		Db::close();
	}

	public function select (string $param): object
	{
		$this->cmd .= "SELECT {$param} FROM {$table} ";
		return $this;
	}

	public function where (string $column, string $operator, string|int|float $value): object
	{
		$this->cmd .= "WHERE {$column} {$operator} {$value} ";
		return $this;
	}

	public function first (): object
	{
		$this->cmd .= "LIMIT 1";
		return Db::query($this->sql);
	}

	public function asc (string $tablename)
	{
		if(empty($tablename))
		{
			$tablename = $this->table;
		}

		$this->cmd .= "ORDER BY {$tablename} ASC ";
	}

	public function sql (): string
	{
		return $this->cmd;
	}
}

?>