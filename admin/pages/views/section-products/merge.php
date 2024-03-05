{% block form-body %}

<p>Seleziona l'altro articolo da unificare a questo prodotto. Il nome mantenuto sarà quello del prodotto selezionato qui sotto.</p>

<?= render_old_search_input($searchInputConfig); ?>

{% end form-body %}


{% block form-footer %}

<button type="submit" class="button" name="submit">Unifica</button>

{% end form-footer %}