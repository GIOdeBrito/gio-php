<?php

namespace GioPHP\Helpers;

function getControllerSchemas (string $controller): array
{
	$reflect = new \ReflectionClass($controller);
	$routeAttributes = [];

	foreach($reflect->getMethods() as $method):

		$attributes = $method->getAttributes();

		if(empty($attributes))
		{
			continue;
		}

		foreach($attributes as $attribute):

			$route = $attribute->newInstance();
			$route->functionName = $method->getName();

			array_push($routeAttributes, $route);

		endforeach;

	endforeach;

	return $routeAttributes;
}

function getSchemaMethod (string $type): string
{
	return mb_strtolower(explode(':', $type)[0], 'UTF-8');
}

function getSchemaTypes (string $schema): array
{
	$schemaMethod = getSchemaMethod($schema);

	$names = str_replace("{$schemaMethod}:", '', $schema);
	$namesNoMultiple = str_replace('[]', '', $names);

	return explode('|', $namesNoMultiple);
}

?>