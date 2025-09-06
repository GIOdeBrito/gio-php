<?php

namespace GioPHP\Error;

function ErrorHandler ()
{
	// Convert errors into exceptions
	set_error_handler(function ($severity, $message, $file, $line)
	{
		if (!(error_reporting() & $severity)) {
			return;
		}

		$output = "GioPHP ERROR -> {$message}. File: {$file}. Line: {$line}";

		error_log($output);

		throw new ErrorException($message, 0, $severity, $file, $line);
	});
}

function PHPShutdownHandler ()
{
	register_shutdown_function(function ()
	{
		$error = error_get_last();

		if(is_null($error) || $error['type'] !== E_ERROR)
		{
			return;
		}

		$file = $error['file'];
		$line = $error['line'];
		$message = $error['message'];

		$output = "GioPHP ERROR -> {$message}. File: {$file}. Line: {$line}";

		error_log($output);

		echo "Internal Server Error. Contact the webmaster.";
		die();
	});
}

?>