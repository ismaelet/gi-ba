{% block main %}

<!-- Background -->
<div id="bgr" class="bgr"></div>

<!-- Content -->
<div class="grid hei100">

	<div class="txt-cen">

		<img src="<?= client_url('/app/resources/logo/logo-c1.png') ?>" id="logo" class="inlb">

		<h1 id="title" class="txt-blk mart1 txt-upp txt-bold">Sito Offline</h1>

		<p class="txt-blk txt-12rem mwid650 pado2 mart1">
			Il nostro sito è al momento in offline per un aggiornamento. Torneremo attivi non appena possibile.
		</p>

	</div>

</div>

<!-- Login button -->
<form id="login" class="wid100 txt-cen" method="POST">

	<button type="submit" name="submit" id="login-txt" class="clickable txt-09rem">

		<span class="btn-icon bgr-c1 bgr-2 small" <?= icon_svg('security/key') ?>></span>
		<span class="btn-txt txt-c1 br-c1 brb1 brb-dashed">Login come amministratore</span>

	</button>

</form>

{% end main %}