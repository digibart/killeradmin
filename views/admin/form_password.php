<form method="post" action="<?= url::site('admin/main/forgot'); ?>" class="validate">
<dl>
	<dt><?= ucfirst(__('username'));?></dt>
	<dd><input type="text" name="username" class="required"></dd>
	<dt><?=  ucfirst(__('email'));?></dt>
	<dd><input type="text" name="email" class="required email"></dd>
</dl>
<button type="submit"><?= __('new password'); ?></button>
</form>
