<form method="post" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login'))); ?>">
<dl>
	<dt><?php echo ucfirst(__('username'));?></dt>
	<dd><input type="text" name="username" class="required"></dd>
	<dt><?php echo  ucfirst(__('password'));?></dt>
	<dd><input type="password" name="password" class="required"> (<?php echo html::anchor(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'forgot')), __('forgot password'));?>)</dd>
	<dt><?php echo  ucfirst(__('remember me'));?></dt>
	<dd><input type="checkbox" name="remember" id="checkbox_1" class="checkbox" /></dd>

</dl>
<button type="submit"><?php echo __('login'); ?></button>
</form>
