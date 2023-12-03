{% block main %}

<div id="bgr" class="bgr"></div>

<!-- Content -->
<div class="grid">

	<div class="txt-cen padv2">

		<div id="logo" class="inlb img cont" <?= img_css('/app/resources/logo/logo-wht.svg'); ?>></div>

		<h1 id="title" class="txt-wht txt-5rem txt-upp mart1 txt-bold pado1">Pagina Offline</h1>

		<p id="stitle" class="wrap550 txt-lh15 txt-wht txt-12rem pado2 mart1">Questa pagina è attualmente in costruzione. A breve sarà di nuovo online. Ci scusiamo per l'inconveniente.</p>

		<a href="<?= client_link('') ?>" class="sbtn mart2 txt-wht br-wht">Torna alla home</a>

	</div>

</div>

{% end main %}