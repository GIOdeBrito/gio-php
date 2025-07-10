<?php

$app->components()->register('button-icon', function ($value = '', $icon = '', $attributes = '')
{
	?>
	<button <?= $attributes ?>>
		<img src="<?= $icon ?>" alt="<?= $value ?? $icon ?>">
		<?= $value ?>
	</button>
	<?php
});

$app->components()->register('button-icon-2', function ($value = '', $icon = '', $attributes = '')
{
	?>
	<button <?= $attributes ?>>
		<img src="<?= $icon ?>" alt="<?= $value ?? $icon ?>">
		<?= $value ?>
	</button>
	<?php
});

?>