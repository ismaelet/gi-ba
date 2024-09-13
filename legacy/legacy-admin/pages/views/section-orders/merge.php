{% block form-body %}

<?php if ($success) { ?>
	<p class="confirmation-message">Ordini uniti con successo</p>
<?php } ?>

<?php if ($error == 'first-order-not-found') { ?>
	<p class="error-warning">Primo ordine non trovato</p>
<?php } ?>

<?php if ($error == 'second-order-not-found') { ?>
	<p class="error-warning">Secondo ordine non trovato</p>
<?php } ?>

<div>
	<label for="firstId">ID primo ordine</label>
	<input type="number" name="firstId" value="" placeholder="ID primo ordine">
</div>

<div>
	<label for="secondId">ID secondo ordine</label>
	<input type="number" name="secondId" value="" placeholder="ID secondo ordine">
</div>

{% end form-body %}


{% block form-footer %}

<button type="submit" class="button" name="submit">Unifica</button>

{% end form-footer %}