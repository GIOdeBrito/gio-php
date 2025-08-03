<?php

use GioPHP\DOM\Component;

return new Component(
	tag: 'button-icon',
	template: __DIR__.'/template.php',
	params: ['id', 'value', 'data-id']
);

?>