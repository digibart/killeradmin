<div class="last twelve columns prefix-6">
	<fieldset>
		<legend><?php echo ucfirst(__('new password')); ?></legend>
		<form method="post" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'forgot'))); ?>" class="validate">
			<label for="username"><?php echo ucfirst(__('username'));?></label>
			<input type="text" name="username" id="username" class="required">
			
			<label><?php echo  ucfirst(__('email'));?></label>
			<input type="text" name="email" class="required email">
			
			<?php if (isset($captcha) && $captcha) : ?>
				<label for="captcha"><?php echo ucfirst(__('security question'));?></label>
				<span class="info">
				<?php echo $captcha; ?><br>
				<?php echo __('enter the code above');?>:<br>
				<input type="text" name="captcha" class="required" id="captcha" size="6">
				</span>
			<?php endif; ?>		
		
			<div class="button bar">
				<?php echo html::anchor(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login')), __('go back'), array('class' => 'button'));?>
				<button type="submit" class="nice primary"><?php echo ucfirst(__('new password')); ?></button>
			</div>
		</form>
	</fieldset>
</div>
