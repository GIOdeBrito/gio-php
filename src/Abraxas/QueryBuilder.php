<?php

namespace GioPHP\Abraxas;

use GioPHP\Abraxas\Db;

abstract class QueryBuilder
{
	private array $cmd = [];
	private string $table;
	private array $properties;
	private array $sqlParams = [];

	public function __construct (string $table, array $properties = [])
	{
		if(!Db::open())
		{
			echo "Could not open DB connection";
		}

		$this->table = $table;
		$this->properties = $properties;
	}

	public function __destruct ()
	{
		Db::close();
	}

	// Basic SELECT statement
	public function select (...$columns): object
	{
		if(is_null($columns) || empty($columns))
		{
			$columns = implode(',', $this->properties);
		}
		else
		{
			$columns = implode(',', $columns);
		}

		array_push($this->cmd, "SELECT {$columns} FROM {$this->table}");
		return $this;
	}

	public function where (string $column, string|int|float $operator, string|int|float|null $value = NULL): object
	{
		// The default operator is the equal sign
		if(is_null($value))
		{
			$value = $operator;
			$operator = '=';
		}

		array_push($this->sqlParams, $value);

		array_push($this->cmd, "WHERE {$column} {$operator} ?");

		return $this;
	}

	public function and (string $column, string|int|float $operator, string|int|float|null $value = NULL): object
	{
		// The default operator is the equal sign
		if(is_null($value))
		{
			$value = $operator;
			$operator = '=';
		}

		array_push($this->sqlParams, $value);

		array_push($this->cmd, "AND {$column} {$operator} ?");

		return $this;
	}

	public function asc (...$tablenames)
	{
		$tables = empty($tablenames) ? 1 : implode(',', $tablenames);
		array_push($this->cmd, "ORDER BY {$tables} ASC");
		return $this;
	}

	public function desc (...$tablenames)
	{
		$tables = empty($tablenames) ? 1 : implode(',', $tablenames);
		array_push($this->cmd, "ORDER BY {$tables} DESC");
		return $this;
	}

	public function sql (): string
	{
		return implode(' ', $this->cmd);
	}

	// Gets first item from query
	public function first (): array|object
	{
		array_push($this->cmd, "LIMIT 1");
		return Db::query($this->sql(), $this->sqlParams);
	}

	// Performs the query
	public function get (): array
	{
		return Db::query($this->sql(), $this->sqlParams);
	}

	// Returns the query as an object array
	public function object (): array
	{
		return Db::query($this->sql(), $this->sqlParams, true);
	}
}

?>