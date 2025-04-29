<?php

namespace GioPHP\Abraxas;

use GioPHP\Abraxas\QueryBuilder;

abstract class Model extends QueryBuilder
{
	private array $properties = [];
	private string $table;

	public function __construct (?string $table = NULL)
	{
		$this->table = get_class($this);

		if(!is_null($table))
		{
			$this->table = $table;
		}

		$this->getPublicProperties();

		parent::__construct($this->table, $this->properties);
	}

	private function getPublicProperties ()
	{
		$reflect = new \ReflectionClass($this);
		$properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

		foreach($properties as $item)
		{
			array_push($this->properties, (object)[ 'name' => $item->name, 'value' => $this->{$item->name} ]);
		}
	}
}

?>