<header class="<?= $h_theme['color'] ?> <?= $h_theme['floating'] ? 'floating' : '' ?> <?= $h_theme['sticky'] ? 'sticky' : '' ?>">

	<div id="h-content">

		<div class="background bgr"></div>

		<!-- Left bar -->
		<div class="nav-bar txt-lef">

			<!-- Logo -->
			<?php if ($h_comp['logo']) { ?>

				<a href="<?= client_link() ?>">
					<img src="<?= $h_theme['color'] == 'light' ? client_url(LOGO_DIR . 'logo-header-light.png') : client_url(LOGO_DIR . 'logo-header-dark.png') ?>" class="logo">
				</a>

			<?php } ?>

			<!-- Name -->
			<?php if ($h_comp['name-bar']) { ?>

				<div class="name-box">

					<a href="<?= client_link() ?>">
						<h2 class="name desk1024"><?= $h_name ?></h2>
					</a>

					<a href="<?= client_link() ?>">
						<h2 class="name mob1024"><?= $h_name_m ?></h2>
					</a>

				</div>

			<?php } ?>

		</div>

		<!-- Middle bar -->
		<div class="nav-bar txt-cen"></div>

		<!-- Right bar -->
		<div class="nav-bar txt-rig">

			<?php if (!$h_comp['force-mob']) { ?>

				<!-- Links -->
				<div id="h-links" class="desk1024 inlb">

					<?php foreach ($h_links as $h_link) {

						if (!isset($h_link['mobile']) || !$h_link['mobile']) { ?>

							<!-- Link -->
							<div class="link">

								<a href="<?= $h_link['url']; ?>">
									<h1 class="title <?= $view->loc == $h_link['loc'] ? 'active' : '' ?>">
										<?= $h_link['title'] ?>
										<?= isset($h_link['drop']) ? '<span class="ddown-icon">&#x25BC;</span>' : '' ?>
									</h1>
								</a>

								<?php if (isset($h_link['drop'])) { ?>

									<!-- Dropdown menu -->
									<div class="ddown-menu bgr-c1">

										<?php foreach ($h_drop[$h_link['drop']] as $h_drop_link) { ?>
											<a href="<?= $h_drop_link[1]; ?>">
												<p class="ddown-link"><?= $h_drop_link[0]; ?></p>
											</a>
										<?php } ?>

									</div>

								<?php } ?>

							</div>

						<?php } ?>
					<?php } ?>
				</div>

			<?php } ?>

			<!-- Language icon -->
			<?php if ($h_comp['lang-bar'] && sysData('multi_lang')) { ?>

				<div id="h-lang-icon" class="inlb">

					<div class="img" <?= icon_css('languages/' . sysData('lang')) ?>></div>

				</div>

			<?php } ?>

			<!-- Cart icon -->
			<?php if ($user->cart_count) { ?>

				<a href="<?= client_link('cart') ?>" <?= $this->header_color == 'light' ? img_css(__ICON__ . 'buy/bag_blk.png') : img_css(__ICON__ . 'buy/bag_wht.png') ?> id="h-cart" class="bag">
					<p class="num"><?= $user->cart_count ?></p>
				</a>

			<?php } ?>

			<!-- Mobile menu icon -->
			<input type="checkbox" id="h-mob-open" class="hide" onclick="$('header').toggleClass('open');">

			<label id="h-mob-icon" class="<?= $h_comp['force-mob'] ? '' : 'mob1024' ?>" for="h-mob-open">
				<div class="line-box">
					<div class="line"></div>
				</div>
				<div class="line-box">
					<div class="line"></div>
				</div>
				<div class="line-box">
					<div class="line"></div>
				</div>
			</label>

		</div>

		<!-- Mobile menu -->
		<div id="h-mob-menu" class="hanging-menu <?= $h_comp['force-mob'] ? '' : 'mob1024' ?>" onClick="$('#h-mob-open').click();">

			<div class="background bgr"></div>

			<!-- Links -->
			<div id="h-mob-links">

				<?php foreach ($h_links as $h_link) { ?>

					<!-- Link -->
					<label class="link">

						<input type="checkbox" class="hide">

						<a <?= isset($h_link['drop']) ? '' : 'href="' . $h_link['url'] . '"' ?>>
							<p class="title txt-upp">
								<?= $h_link['title'] ?>
								<?= isset($h_link['drop']) ? '<span class="h-dropM-ico">&#x25BC;</span>' : '' ?>
							</p>
						</a>


						<?php if (isset($h_link['drop'])) { ?>

							<!-- Dropdown menu -->
							<div class="ddown-menu">

								<?php foreach ($h_drop[$h_link['drop']] as $h_drop_link) { ?>

									<p class="ddown-link txt-wht-h bgr-c3-h">
										<a href="<?= $h_drop_link[1]; ?>">
											<?= $h_drop_link[0]; ?>
										</a>
									</p>

								<?php } ?>
							</div>

						<?php } ?>

					</label>

				<?php } ?>

			</div>
		</div>

		<!-- Language menu -->
		<?php if ($h_comp['lang-bar'] && sysData('multi_lang')) { ?>
			<div id="h-lang-menu" class="hanging-menu">

				<div class="box">
					<?php foreach (sysData('lang_all') as $key => $langM) { ?>

						<div class="grid language <?= sysData('lang') == $langM[1] ? 'active' : '' ?>">

							<div class="">

								<div class="img" <?= icon_css('languages/' . $langM[1]) ?>></div>

							</div>

							<p class="name"><?= $langM[0] ?></p>
						</div>

					<?php } ?>
				</div>

			</div>
		<?php } ?>

	</div>
</header>