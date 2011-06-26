<h3>Step 1: Checking modules</h3>
<ul>
	<?php foreach ($modules as $module => $exists) : ?>
		<li><?php echo $module; ?> : <?php echo KillerAdmin::spriteImg(($exists) ? 'tick' : 'cross'); ?></li>
	<?php endforeach; ?>
</ul>

<h3>Step 2: Checking  config</h3>
<ul>
	<li>auth['hash_key']: <?php echo $config['auth']; ?></li>
	<li>admin: <?php echo $config['admin']; ?></li>
</ul>

<hr>
<h3>Step 3: Create user</h3>
<form method="post" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller' => 'setup', 'action' => 'create_user'))); ?>">
	<table>
		<tr>
			<td>Username:</td>
			<td><input type="text" name="username" value="<?php echo HTML::chars(Arr::get($post_data,'username')); ?>"></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input type="text" name="email" value="<?php echo HTML::chars(Arr::get($post_data,'email')); ?>"></td>
		</tr>
	</table>
	<?php echo ($errors == 0) ? '<input type="submit" class="button" value="create user">' : "Fix the errors first"; ?>
</form>
