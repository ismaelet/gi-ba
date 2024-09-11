{% block main %}

<div id="bgr" class="bgr"></div>

<!-- Content -->
<div class="grid">

	<div class="txt-cen padv2">

		<div id="logo" class="inlb img cont" <?= img_css('/app/resources/logo/logo-wht.svg');?>></div>

		<h1 id="title" class="txt-wht txt-5rem txt-upp mart1 txt-bold pado1">Coming Soon</h1>

		<p id="stitle" class="wrap480 txt-lh15 txt-wht txt-12rem pado2 mart1">Questa pagina è attualmente in costruzione. Torneremo online non appena possibile.</p>

	</div>
	
</div>

<!-- Login button -->
<form id="login" class="wid100 txt-cen" method="POST">

	<button type="submit" name="submit" id="login-txt" class="clickable txt-09rem">

		<span class="btn-icon bgr-c2" <?= icon_svg('security/key');?>></span>
		<span class="btn-txt txt-c2 br-c2 brb1 brb-dashed">Login as administrator</span>

	</button>

</form>

{% end main %}