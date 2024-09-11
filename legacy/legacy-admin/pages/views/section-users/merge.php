{% block form-body %}

<p>Seleziona l'altro cliente a cui unificare questo cliente. Il cliente mantenuto sarà quello selezionato qui sotto, incluse le note e il numero di telefono.</p>

<?= render_search_input($searchInputConfig); ?>

{% end form-body %}


{% block form-footer %}

<button type="submit" class="button" name="submit">Unifica</button>

{% end form-footer %}