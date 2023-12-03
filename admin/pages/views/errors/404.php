{% block main %}

<div class="bgr" id="background" <?= background_image('bgr.jpg') ?>></div>

<div class="hei-100 center-content txt-cen" id="body">
	<h1 class="txt-upp txt-bold pad-h-1" id="title"><?= __('Error 404') ?></h1>
	<p class="txt-15rem mar-t-1" id="stitle"><?= __('Page not found') ?></p>
	<a class="go-back sbtn mar-t-2 mar-h-auto txt-1rem txt-upp" href="<?= client_url('/') ?>"><?= __('Back to home') ?></a>
</div>

{% end main %}