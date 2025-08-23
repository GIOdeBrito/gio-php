<?php

// Template for ButtonIcon

?>
<button <?= $id ?> <?= $attributes ?>>
	<img src="<?= $icon ?>" alt="<?= $icon ?? '' ?>">
	<?= $value ?>
</button>