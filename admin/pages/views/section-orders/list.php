{% block filter %}

<div class="filters mar-t-1 grid-flow" data-spacing="700">

	<!-- Date -->
	<div class="filter">
		<div class="filter__title">
			<h3 class="fw-bold flex-group">
				Data

				<?php if ($startDate != $minimumStartDate || $endDate != $currentDate) { ?>

					<span class="interactive js-remove-date-filter">
						<?= icon_embed('close') ?>
					</span>

				<?php } ?>

			</h3>
		</div>

		<div class="flex-group mar-t-1" data-spacing="200">
			<p>dal</p>
			<input type="date" class="filter-date js-filter-date" name="start-date" value="<?= $startDate ?>">
			<p>al</p>
			<input type="date" class="filter-date js-filter-date" name="end-date" value="<?= $endDate ?>">
		</div>
	</div>

	<!-- Status -->
	<div class="filter">
		<div class="filter__title">
			<h3 class="fw-bold flex-group">
				Stato

				<?php if ($selectedCartStatuses != $defaultCartStatuses) { ?>

					<span class="interactive js-remove-status-filter">
						<?= icon_embed('close') ?>
					</span>

				<?php } ?>

			</h3>
		</div>

		<div class="flex-group mar-t-1" data-spacing="200">
			<input type="checkbox" class="hidden js-empty-filter-status" name="filter-status[]" value="0" <?= in_array(0, $selectedCartStatuses) ? 'checked' : '' ?> data-default-status="0">

			<?php foreach ($statuses as $statusId => $status) { ?>

				<label class="status-filter checkbox">
					<input type="checkbox" class="js-status-filter" name="filter-status[]" value="<?= $statusId ?>" <?= in_array($statusId, $selectedCartStatuses) ? 'checked' : '' ?> data-default-status="<?= $statusId < 7 ? 1 : 0 ?>">
					<span></span>
					<p class=""><?= $status[1] ?></p>
				</label>

			<?php } ?>

			<label class="status-filter checkbox">
				<input type="checkbox" class="js-status-all" value="all" <?= $statusIds == $selectedCartStatuses ? 'checked' : '' ?> data-default-status="0">
				<span></span>
				<p class="">Tutti</p>
			</label>

		</div>
	</div>

	<!-- Billing -->
	<div class="filter">
		<div class="filter__title">
			<h3 class="fw-bold flex-group">
				Fattura

				<?php if ($selectedBilledStatuses != $defaultBilledStatuses) { ?>

					<span class="interactive js-remove-billed-filter">
						<?= icon_embed('close') ?>
					</span>

				<?php } ?>

			</h3>
		</div>

		<div class="flex-group mar-t-1" data-spacing="200">
			<label class="billed-filter checkbox">
				<input type="checkbox" class="js-billed-filter" name="filter-billed[]" value="0" <?= in_array(0, $selectedBilledStatuses) ? 'checked' : '' ?>>
				<span></span>
				<p class="">Non fatturato</p>
			</label>

			<label class="billed-filter checkbox">
				<input type="checkbox" class="js-billed-filter" name="filter-billed[]" value="1" <?= in_array(1, $selectedBilledStatuses) ? 'checked' : '' ?>>
				<span></span>
				<p class="">Fatturato</p>
			</label>
		</div>
	</div>
</div>

{% end filter %}