<?php

function ButtonIcon ($value = '', $icon = '', $attributes = '')
{
	?>
	<button <?= $attributes ?>>
		<img src="<?= $icon ?>" alt="<?= $icon ?? '' ?>">
		<?= $value ?>
	</button>
	<?php
}

$app->components()->register('button-icon', 'ButtonIcon');

?>