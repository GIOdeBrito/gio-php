<?php

namespace GioPHP\Abraxas;

use GioPHP\Abraxas\Db;
use GioPHP\Helpers\StringTools;

abstract class QueryBuilder
{
	private string $cmd;
	private string $table;
	private array $properties;
	private array $sqlParams = [];

	public function __construct (string $table, array $properties = [])
	{
		if(!Db::open())
		{
			echo "Could not open DB connection";
		}

		$this->cmd = "";
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

		$this->cmd .= "SELECT {$columns} FROM {$this->table} ";
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

		$this->cmd .= "WHERE {$column} {$operator} ? ";

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

		$this->cmd .= "AND {$column} {$operator} ? ";

		return $this;
	}

	// TODO: Implement ordering with multiple tables
	public function asc (...$tablenames)
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

	// Gets first item from query
	public function first (): array|object
	{
		$this->cmd .= "LIMIT 1";
		return Db::query($this->sql(), $this->sqlParams);
	}

	public function get (): array|object
	{
		return Db::query($this->sql(), $this->sqlParams);
	}
}

?>