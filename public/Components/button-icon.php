<?php

$app->components()->register('ButtonIcon', function ($value, $icon, $attributes)
{
	?>
	<button <?= $attributes ?>>
		<img src="<?= $icon ?>" alt="<?= $value ?? $icon ?>">
		<?= $value ?>
	</button>
	<?php
});

?>