<?php

namespace GioPHP\Helpers;

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

		case 'date':
			return null;
			break;

		case 'any':
		case 'string':
		default:
			return strval($value);
			break;
	}
}

?>