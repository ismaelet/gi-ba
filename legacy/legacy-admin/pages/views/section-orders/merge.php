{% block form-body %}

<?php if ($success) { ?>
	<p class="confirmation-message">Ordini uniti con successo</p>
<?php } ?>

<?php if ($error == 'first-order-not-found') { ?>
	<p class="error-warning">Ordine principale non trovato</p>
<?php } ?>

<?php if ($error == 'second-order-not-found') { ?>
	<p class="error-warning">Ordine da unire non trovato</p>
<?php } ?>

<div>
	<label for="firstId">ID ordine principale</label>
	<input type="number" name="firstId" value="" placeholder="ID ordine principale">
</div>

<div>
	<label for="secondId">ID ordine da unire</label>
	<input type="number" name="secondId" value="" placeholder="ID ordine da unire">
</div>

{% end form-body %}


{% block form-footer %}

<button type="submit" class="button" name="submit">Unifica</button>

{% end form-footer %}