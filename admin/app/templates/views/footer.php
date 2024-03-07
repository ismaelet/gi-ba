<footer>
	<div class="main-wrap">

		<!-- Columns -->
		<div class="f-columns">

			<?php foreach ($footer['columns'] as $f_column) { ?>

				<div class="f-column">
					<h3 class="f-title"><?= $f_column['title'] ?></h3>

					<?php foreach ($f_column['links'] as $f_link) { ?>

						<h3 class="f-link">
							<?php if (isset($f_link[1])) { ?>
								<a href="<?= $f_link[1] ?>">
									<?= $f_link[0] ?>
								</a>
							<?php } else { ?>
								<?= $f_link[0] ?>
							<?php } ?>
						</h3>

					<?php } ?>
				</div>

			<?php } ?>

			<!-- Contact icons -->
			<div class="f-column">
				<h3 class="f-title">Contatti</h3>
				<div class="f-socials">
					<a href="<?= sys_config('cont-call-link') ?>" class="f-social icon-box">
						<?= icon_embed('contacts/call') ?>
					</a>
					<a href="<?= sys_config('cont-telegram-link') ?>" class="f-social icon-box">
						<?= icon_embed('contacts/telegram') ?>
					</a>
					<a href="<?= sys_config('cont-email-link') ?>" class="f-social icon-box">
						<?= icon_embed('contacts/mail') ?>
					</a>
					<a href="<?= sys_config('cont-instagram-link') ?>" target="_blank" class="f-social icon-box">
						<?= icon_embed('contacts/instagram') ?>
					</a>
					<a href="<?= sys_config('cont-facebook-link') ?>" target="_blank" class="f-social icon-box">
						<?= icon_embed('contacts/facebook') ?>
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Notes -->
	<div class="f-notes">
		<div class="main-wrap">
			<p class="f-notes-text">
				Copyright © <?= getdate()['year'] ?> <?= sys_config('name-full') ?></a>
				<br>Sito web realizzato da <a class="txt-f-h" href="mailto:ismaele.tuffanelli@gmail.com">Ismaele Tuffanelli</a>
			</p>
		</div>
	</div>

</footer>