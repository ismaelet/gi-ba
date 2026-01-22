{% block main %}

<!-- Slider -->
<section id="slider">

	<div class="bgr bgr-f3" <?= img_css('bgr.jpg', $view) ?>></div>

	<div class="title-box cenY rel txt-cen">

		<h1 class="title txt-wht txt-bold marb2">Galleria</h1>

	</div>

</section>

<!-- Collections -->
<section id="collections" class="padt4 padb5 content-wrap">

	<p class="txt-1rem txt-sbold txt-c2 txt-cen pado2 pado1-480w padv5px">Se vedi qualcosa che ti piace <a href="<?= client_link('#contacts') ?>" class="txt-bold txt-c1">contattaci</a> per un preventivo o vieni a scoprire questi e molti altri articoli nel nostro negozio</p>

	<div class="mart3 maro-1 txt-0rem txt-cen">

		<?php while ($collection = $collections->fetch_assoc()) { ?>

			<!-- Image -->
			<div class="box pad1 ele3 ele2-1440w ele1-480w">

				<a href="<?= client_link('collection?id=' . $collection['id']) ?>" class="collection block bgr-f3">

					<img src="<?= client_url('/uploads/collections/' . $collection['id'] . '_m.jpg') ?>" alt="" loading="lazy" class="img-cover">

					<div class="bgr overlay"></div>
					<div class="bgr overlay-h"></div>

					<h1 class="name cenY wid100 pado1 txt-wht txt-bold txt-25px"><?= $collection['name'] ?></h1>

				</a>

			</div>

		<?php } ?>

	</div>

	</div>

</section>

{% end main %}