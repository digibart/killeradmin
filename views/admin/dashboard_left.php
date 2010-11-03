<div class="span-7 box">
		<H4><?php echo ucfirst(__('userinfo')); ?></H4>
		<strong><?php echo ucfirst(__('last login')); ?></strong>: <?php echo strftime("%a %d %b %R", $user->last_login); ?>
		<strong><?php echo ucfirst(__('username')); ?></strong>: <?php echo $user->username; ?>
</div>