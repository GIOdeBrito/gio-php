<?php

namespace GioPHP\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    public function __construct(
		public string $method = '',
		public string $path = '',
        public array $schema = [],
		public string $description = '',
		public bool $isError = false,
		public bool $isStatic = false,

		public string $functionName = '',
    ) {}
}

?>