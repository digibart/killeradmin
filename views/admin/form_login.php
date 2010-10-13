<form method="post" action="<?= url::site('admin/main/login'); ?>">
<dl>
	<dt><?= ucfirst(__('username'));?></dt>
	<dd><input type="text" name="username" class="required"></dd>
	<dt><?=  ucfirst(__('password'));?></dt>
	<dd><input type="password" name="password" class="required"> (<?= html::anchor('admin/main/forgot', __('forgot password'));?>)</dd>
	<dt><?=  ucfirst(__('remember me'));?></dt>
	<dd><input type="checkbox" name="remember" id="checkbox_1" class="checkbox" /></dd>

</dl>
<button><?= __('login'); ?></button>
