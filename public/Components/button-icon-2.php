<?php

return [
	'selector' => 'ButtonIcon',
	'template' => function () {
		?>
		<button <?= $attributes ?>>
			<img src="<?= $icon ?>" alt="<?= $icon ?? '' ?>">
			<?= $value ?>
		</button>
		<?php
	}
];

?>