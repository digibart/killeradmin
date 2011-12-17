<div class="seven columns">
	<form method="post" class="validate" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login'))); ?>">
	
		<label for="username"><?php echo ucfirst(__('username'));?></label>
		<input type="text" name="username" id="username" class="required">
		
		<label for="password"><?php echo  ucfirst(__('password'));?><span><?php echo html::anchor(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'forgot')), __('forgot password'));?></span></label>
		<input type="password" name="password" id="password" class="required">
		
		<?php if (isset($captcha) && $captcha) : ?>
			<label for="captcha" style="height: 100px"><?php echo ucfirst(__('security question'));?></label>
			<?php echo $captcha; ?><br>
			<?php echo __('enter the code above');?>:<br>
			<input type="text" name="captcha" class="required" id="captcha" size="6">
		<?php endif; ?>		
		
		<div class="button bar">
			<button type="submit" class="primary"><?php echo __('login'); ?></button>
		</div>
	
	
	</form>
</div>