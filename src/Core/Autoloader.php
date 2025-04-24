<?php

/* ======
GioPHP Autoloader

Import this file into your application's main entrypoint.
To use it without the need of Composer.
====== */

function __GioPHPAutoloader__ ($classname)
{
	// Get the root folder of the framework
	$root = __DIR__.'/../';

	// Splits the path to the selected class
	$paths = explode('/', str_replace('\\', DIRECTORY_SEPARATOR, $classname));

	// Removes the first item of the array
    array_shift($paths);

	$classPath = implode('/', $paths);

	// Searches for the file within GioPHP's folder
	$path = $root.str_replace('\\', DIRECTORY_SEPARATOR, $classPath).'.php';

	if(!file_exists($path))
	{
		throw new Exception("Class '{$classname}' not found");
		return;
	}

	require $path;
}

spl_autoload_register('__GioPHPAutoloader__');

?>