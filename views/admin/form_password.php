<form method="post" action="<?php echo url::site('admin/main/forgot'); ?>" class="validate">
<dl>
	<dt><?php echo ucfirst(__('username'));?></dt>
	<dd><input type="text" name="username" class="required"></dd>
	<dt><?php echo  ucfirst(__('email'));?></dt>
	<dd><input type="text" name="email" class="required email"></dd>
</dl>
<button type="submit"><?php echo __('new password'); ?></button>
</form>
