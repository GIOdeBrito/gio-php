<?php

namespace GioPHP\Helpers;

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