<?php

return [
	'selector' => 'button-icon',
	'template' => function ($value, $icon, $attributes) {
		?>
		<button <?= $attributes ?>>
			<img src="<?= $icon ?>" alt="<?= $icon ?? '' ?>">
			<?= $value ?>
		</button>
		<?php
	}
];

?>