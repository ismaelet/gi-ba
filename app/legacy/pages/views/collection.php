{% block main %}

<!-- Slider -->
<section id="slider">

	<div class="background bgr bgr-2">
		<div class="image bgr" <?= img_css('/uploads/collections/' . $collection['id'] . '.jpg') ?>></div>
	</div>

	<div class="title-box cenY rel txt-cen">

		<h1 class="title txt-wht wrap txt-bold marb2"><?= $collection['name'] ?></h1>

	</div>

</section>

<!-- Gallery -->
<section id="gallery" class="padt4 padb5 content-wrap" data-count="<?= $images->num_rows ?>">

	<p class="txt-1rem txt-sbold txt-c2 txt-cen pado2 pado1-480w padv5px">Se vedi qualcosa che ti piace <a href="<?= client_link('#contacts') ?>" class="txt-bold txt-c1">contattaci</a> per un preventivo o vieni a scoprire questi e molti altri articoli nel nostro negozio</p>

	<div class="txt-cen marv2">
		<a href="<?= client_link('gallery') ?>" class="sbtn">Torna alle collezioni</a>
	</div>

	<div class="maro-1 txt-0rem">

		<?php

		$i = 0;

		while ($image = $images->fetch_assoc()) {

			$i++;

			$imagePath = '/uploads/images/' . $image['id'] . '_m.webp';
			$imageClientPath = file_exists(base_path() . '/public/legacy' . $imagePath) ? client_url($imagePath) : client_url('/uploads/images/' . $image['id'] . '_m.jpg');
		?>

			<!-- Image -->
			<div class="image-box pad1 ele4 ele3-1024w ele2-480w" data-pos="<?= $i ?>">

				<div id="image<?= $i ?>" class="image bgr-f3 lbox-open clickable" data-tar="#lbox<?= $image['id'] ?>">

					<img src="<?= $imageClientPath ?>" alt="" loading="lazy" class="img-cover">

					<div class="bgr overlay"></div>

					<div class="icon bgr-wht" <?= icon_svg('actions/expand-round') ?>></div>

				</div>

				<!-- Lightbox -->
				<div id="lbox<?= $image['id'] ?>" class="lbox">

					<div class="lbox-bgr"></div>

					<div class="lbox-close-icon"></div>

					<img src="<?= str_replace('_m', '', $imageClientPath) ?>" alt="" class="lbox-img lbox-close clickable">

					<?php if ($i > 1) { ?>
						<div class="arrow left cenY">
							<div class="icon cen bgr-wht clickable" <?= icon_svg('navigation/arrow1-right') ?>></div>
						</div>
					<?php } ?>

					<?php if ($i < $images->num_rows) { ?>
						<div class="arrow right cenY">
							<div class="icon cen bgr-wht clickable" <?= icon_svg('navigation/arrow1-right') ?>></div>
						</div>
					<?php } ?>

				</div>

			</div>

		<?php } ?>

	</div>

</section>

{% end main %}