{% block content %}

<div class="grid-flow" data-columns="25rem" data-spacing="400">

	<?php

	use function Ecommerce\format_price;

	foreach ($recapData as $statusId => $statusData) { ?>

		<section class="panel recap--status-<?= $statusData['slug'] ?>">
			<div class="body">
				<h2 class="recap_title title"><?= $statusData['title'] ?></h2>
				<h3 class="recap_value"><?= format_price($statusData['value']) ?></h3>

				<?php if ($statusId == 6) { ?>

					<p class="recap__data mar-t-1">fatturato <span class="fw-bold"><?= format_price($withdrawnBilled) ?></span></p>
					<p class="recap__data">da fatturare <span class="fw-bold"><?= format_price($withdrawnNotBilled) ?></span></p>

				<?php } ?>

			</div>
		</section>

	<?php } ?>

</div>

{% end content %}