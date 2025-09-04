<?php

namespace GioPHP\Helpers;

require __DIR__.'/DateTime.php';

use function GioPHP\Helpers\toDateTime;

function convertToType (mixed $value, string $type = 'any'): mixed
{
	switch($type)
	{
		case 'int':
		case 'integer':
			return intval($value);
			break;

		case 'float':
		case 'double':
			return floatval($value);
			break;

		case 'boolean':
		case 'bool':
			return filter_var($value, FILTER_VALIDATE_BOOLEAN);
			break;

		case 'date':
			return toDateTime($value);
			break;

		case 'any':
			return $value;
			break;

		case 'string':
		default:
			return strval($value);
			break;
	}
}

?>