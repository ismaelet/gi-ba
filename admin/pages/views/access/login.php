{% block main %}

<form class="form grid-flow" data-spacing="700" method="POST">

	<div class="form-head">
		<h1 class="form-title load ld-bot">Login</h1>
	</div>

	<div class="form-body grid-flow" data-spacing="400">

		<div class="form-field load ld-lef ld-del1">
			<input type="text" name="username" placeholder="<?= __('Username') ?>">
		</div>

		<div class="form-field load ld-rig ld-del1">

			<input type="password" name="password" placeholder="<?= __('Password') ?>">

			<label class="hidden">
				<input type="checkbox" name="remember" checked>
				<div class="icon"></div>
				<p class="cbx-txt"><?= __('Stay connected') ?></p>
			</label>

			<?php if ($error) { ?>
				<p class="error">Dati di accesso errati</p>
			<?php } ?>

		</div>

	</div>

	<div class="form-foot load ld-top ld-del1">
		<button type="submit" class="button button--full-width" name="submit">Log In</button>
	</div>

</form>

{% end main %}