<?php

namespace GioPHP\Abraxas;

use GioPHP\Abraxas\Db;

abstract class QueryBuilder
{
	private string $table;

	private array $cmd = [];
	private array $sqlParams = [];

	private array $properties = [];
	private array $propertyNames = [];

	public function __construct (string $table, array $properties = [])
	{
		Db::open();

		$this->table = $table;
		$this->properties = $properties;

		foreach($this->properties as $item)
		{
			array_push($this->propertyNames, $item->name);
		}
	}

	public function __destruct ()
	{
		Db::close();
	}

	// SELECT statement
	private function select (...$columns): object
	{
		if(is_null($columns) || empty($columns))
		{
			$columns = implode(',', $this->propertyNames);
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
		if(count($this->cmd) === 0)
		{
			$this->select();
		}

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

	public function andWhere (string $column, string|int|float $operator, string|int|float|null $value = NULL): object
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

	public function orderBy (...$tablenames): object
	{
		if(count($this->cmd) === 0)
		{
			$this->select();
		}

		$tables = empty($tablenames) ? 1 : implode(',', $tablenames);
		array_push($this->cmd, "ORDER BY {$tables}");
		return $this;
	}

	public function asc (): object
	{
		array_push($this->cmd, "ASC");
		return $this;
	}

	public function desc (): object
	{
		array_push($this->cmd, "DESC");
		return $this;
	}

	public function sql (): string
	{
		return implode(' ', $this->cmd);
	}

	// Gets first item from query
	public function first (): array|object
	{
		if(count($this->cmd) === 0)
		{
			$this->select();
		}

		array_push($this->cmd, "LIMIT 1");
		return Db::query($this->sql(), $this->sqlParams);
	}

	public function all (): array|object
	{
		$this->select();
		return $this;
	}

	// Performs the query
	public function get (): array
	{
		return Db::query($this->sql(), $this->sqlParams);
	}

	// Returns the query as an object array
	public function toObject (): array
	{
		return Db::query($this->sql(), $this->sqlParams, true);
	}

	// INSERT/UPDATE statement
	public function save (): void
	{

	}
}

?>