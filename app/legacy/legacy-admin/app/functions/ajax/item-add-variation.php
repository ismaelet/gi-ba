<?php

$name = $_GET['name'];

?>

<div class='variation'>
	<input type='hidden' name='variation-1' value='<?= $name ?>'>
	<h3 class='variation-title'>
		<?= $name ?>
		<button type="button" class="delete-variation icon-box">
			<?= icon_embed('delete') ?>
		</button>
	</h3>

	<!-- Variant list -->
	<div class='variants mar-t-05'>
		<div class='variant'>
			<input type='text' class='inp' name='variation-1-variants[]' placeholder='Nome da variação' required>
			<input type='text' class='inp' name='variation-1-prices[]' placeholder='Preço (opcional)'>
			<button type="button" class="delete-variant icon-box">
				<?= icon_embed('close') ?>
			</button>
		</div>
	</div>

	<!-- Add a variant -->
	<button type='button' class='add-variant icon-box mar-t-05'>
		<?= icon_embed('add') ?>
	</button>
</div>