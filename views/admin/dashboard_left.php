<div class="eight columns">
		<h2><?php echo ucfirst(__('userinfo')); ?></h2>
		<strong><?php echo ucfirst(__('last login')); ?></strong>: <?php echo strftime("%a %d %b %R", $user->last_login); ?><br>
		<strong><?php echo ucfirst(__('username')); ?></strong>: <?php echo $user->username; ?>
</div>