<div class="span-7 box">
		<H4><?= ucfirst(__('userinfo')); ?></H4>
		<strong><?= ucfirst(__('last login')); ?></strong>: <?= strftime("%a %d %b %R", $user->last_login); ?>
		<strong><?= ucfirst(__('username')); ?></strong>: <?= $user->username; ?>
</div>