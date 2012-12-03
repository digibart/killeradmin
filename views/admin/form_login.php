<div class="last twelve columns prefix-6">
	<form method="post" class="validate" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'login'))); ?>">
		<dd>
			<dt class="eight columns"><label for="username"><?php echo ucfirst(__('username'));?></label></dt>
			<dd class="sixteen columns last"><input type="text" name="username" id="username" class="required"></dd>

			<dt class="eight columns"><label for="password"><?php echo  ucfirst(__('password'));?></label></dt>
			<dd class="sixteen columns">
				<input type="password" name="password" id="password" class="required">
				<small><?php echo html::anchor(Route::get('admin/base_url')->uri(array('controller' => 'main', 'action' => 'forgot')), __('forgot password'));?></small>
			</dd>

			<dt class="eight columns"><label for="remember"><?php echo  ucfirst(__('remember me'));?></label></dt>
			<dd class="sixteen columns">
				<input type="checkbox" name="remember" id="remember">
			</dd>

			<?php if (isset($captcha) && $captcha) : ?>
				<dt class="eight columns"><label for="captcha"><?php echo ucfirst(__('security question'));?></label></dt>
				<dd class="sixteen columns last">
							<?php echo $captcha; ?><br>
							<?php echo __('enter the code above');?>:
					<input type="text" name="captcha" class="required" id="captcha" size="6">
				</dd>
			<?php endif; ?>

			<dd class="sixteen last columns prefix-8"><button type="submit" class="nice primary"><?php echo __('login'); ?></button></dd>
		</dd>
	</form>
</div>
