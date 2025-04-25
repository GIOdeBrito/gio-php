<?php

// Make sure to run this file from the command prompt
if(php_sapi_name() !== 'cli')
{
	die("Halted. Run this installer from the command prompt.");
}

echo "GioPHP Installer.".PHP_EOL;

// https://github.com/GIOdeBrito/gio-php/releases/download/stable/gio-php-v1.0.0.tar.gz

$VERSION = "1.0.0";

echo "Fetching GioPHP standalone version: {$VERSION}.".PHP_EOL;

$filename = "gio-php-v{$VERSION}.tar.gz";
$url = "https://github.com/GIOdeBrito/gio-php/releases/download/stable/".$filename;

if(!file_put_contents("gio-php-v{$VERSION}.tar.gz", file_get_contents($url)))
{
	echo "Could not download compressed file.".PHP_EOL;
}

echo "Downloaded compressed file.".PHP_EOL;

// Decompress file
$p = new PharData($filename);
$p->decompress();

$phar = new PharData($filename);
$phar->extractTo("temp_gio", "src/");

// Rename the source folder to GioPHP
rename("temp_gio/src", "GioPHP");
rmdir("temp_gio");

unlink($filename);

echo "Finished installation.".PHP_EOL;

//echo PHP_EOL;

?>