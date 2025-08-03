<?php

// Template for ButtonIcon

?>
<button <?= $attributes ?>>
	<img src="<?= $icon ?>" alt="<?= $icon ?? '' ?>">
	<?= $value ?>
</button>