<div class="last twelve columns prefix-6">
	<form method="post" class="validate" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login'))); ?>">
	
		<label for="username"><?php echo ucfirst(__('username'));?></label>
		<input type="text" name="username" id="username" class="required">
		
		<label for="password"><?php echo  ucfirst(__('password'));?></label>
		<input type="password" name="password" id="password" class="required">
		<small><?php echo html::anchor(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'forgot')), __('forgot password'));?></small>
		
		
		<?php if (isset($captcha) && $captcha) : ?>
		<label for="captcha"><?php echo ucfirst(__('security question'));?></label>
			<span class="info">
				<?php echo $captcha; ?><br>
				<?php echo __('enter the code above');?>:
			<input type="text" name="captcha" class="required" id="captcha" size="6">
			</span>
		<?php endif; ?>		
		
		<div class="button bar">
			<button type="submit" class="nice primary"><?php echo __('login'); ?></button>
		</div>
	</form>
</div>
