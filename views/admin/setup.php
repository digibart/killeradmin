<div class="row">
	<h3>Step 1: Checking modules</h3>
		<?php foreach ($modules as $module => $exists) : ?>
			<div class="row">
				<div class="four columns"><?php echo $module; ?></div><div class="two last columns"><?php echo KillerAdmin::spriteImg(($exists) ? 'tick' : 'cross'); ?></div>
			</div>
		<?php endforeach; ?>


</div>

<h3>Step 2: Checking  config</h3>
<div class="row">
	<div class="four columns">auth['hash_key']:</div><div class="two last columns"><?php echo $config['auth']; ?></div>
</div>
<div class="row">
	<div class="four columns">admin:</div><div class="two last columns"><?php echo $config['admin']; ?></div>
</div>

<hr>
<h3>Step 3: Create user</h3>
<div class="eight columns last">
	<form method="post" class="validate" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller' => 'setup', 'action' => 'create_user'))); ?>">
		<label for="username"><?php echo ucfirst(__('username'));?></label>
		<input type="text" name="username" id="username" class="required" minlength="5">
		
		<label for="email"><?php echo  ucfirst(__('email'));?></label>
		<input type="text" name="email" class="required email">

		<div class="button bar">
			
			<?php if ($errors == 0) : ?>
				<input type="submit" class="primary button" value="Create user">
			<?php else: ?>
				<p class="notice">Fix the errors first before you can continue</p>
			<?php endif; ?>
		</div>

		
	</div>
</form>
