<?php

$app->components()->register('button-icon', function ($value, $icon, $attributes)
{
	?>
	<button <?= $attributes ?>>
		<img src="<?= $icon ?>" alt="<?= $value ?? $icon ?>">
		<?= $value ?>
	</button>
	<?php
});

?>