<?php

/* Component factory namespace */

namespace GioPHP\Components;

use GioPHP\Components\DOM;

class Factory
{
    private array $components = [];
	private string $bufferContent = NULL;
	private callable $destructor = NULL;

	public static function addComponent (string $tagname, callable $callback): void
    {
		if(is_null(self::$destructor))
		{
			self::$destructor = new class {
				public function __destruct ()
				{
					Factory::stopCapture();
				}
			};
		}

		// Checks if the tag already exists or if the function is callable
		if(array_key_exists($tagname, self::$components) || !is_callable($callback))
        {
            return;
        }

        self::$components[$tagname] = $callback;
    }

	private static function htmlComponentFinder ()
	{

	}

	private static function beginCapture (): void
	{
		ob_start();

		// Root node
		echo "<div></div>";
	}

	public static function stopCapture (): void
	{
		self::$bufferContent = ob_get_clean();
		self::htmlComponentFinder();
	}
}

?>