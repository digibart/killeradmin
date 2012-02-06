<div class="last twelve columns prefix-6">
	<form method="post" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'forgot'))); ?>" class="validate">

		<dt class="eight columns"><label  for="username"><?php echo ucfirst(__('username'));?></label>
		<dd class="sixteen columns last"><input type="text" name="username" id="username" class="required">
		
		<dt class="eight columns"><label ><?php echo  ucfirst(__('email'));?></label>
		<dd class="sixteen columns last"><input type="text" name="email" class="required email">
		
		<?php if (isset($captcha) && $captcha) : ?>
		<dt class="eight columns"><label  for="captcha"><?php echo ucfirst(__('security question'));?></label>
		<dd class="sixteen columns last">
		<?php echo $captcha; ?><br>
		<?php echo __('enter the code above');?>:<br>
		<input type="text" name="captcha" class="required" id="captcha" size="6">
		</dd>
		<?php endif; ?>		
	
		<dt class="eight columns"><?php echo html::anchor(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login')), __('go back'), array('class' => 'button'));?></dt>
		<dd class="sixteen columns last"><button type="submit" class="nice primary"><?php echo ucfirst(__('new password')); ?></button></dd>
		</div>
	</form>
</div>
