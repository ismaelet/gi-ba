{% block main %}

<div id="bgr" class="bgr" <?= img_css('bgr.jpg', $view); ?>></div>

<div id="content" class="grid hei100 just-cen">

	<div id="title-box" class="txt-cen">

		<h1 id="title" class="txt-upp txt-wht txt-bold pado1">Error 404</h1>
		<p id="stitle" class="txt-15rem txt-wht mart10px">Page not found</p>

		<p class="button txt-wht txt-1rem txt-upp pado2 inlb mart2 br-c1-h">
			<a href="<?= client_link() ?>">Back to the homepage</a>
		</p>

	</div>

</div>

{% end main %}