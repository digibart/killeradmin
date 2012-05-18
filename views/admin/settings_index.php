<div class="twelve columns prefix-6">
	<form method="post" autocomplete="off" class="validate" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller'=>'settings','action'=>'save')));?>">
		<dl>
			<dt class="eight columns"><label><?php echo ucfirst(__('username'));?></label></dt>
			<dd class="twelve last columns"><?php echo $user->username;?></dd>

			<dt class="eight columns"><label><?php echo ucfirst(__('email'));?></label></dt>
			<dd class="twelve last columns"><input type="text" name="email" class="required email" autocomplete="off" value="<?php echo $user->email; ?>"></dd>

			<dt class="eight columns"><label><?php echo ucfirst(__('password'));?> <span><?php echo __(':min-:max characters', array(':min' => 5, ':max' => 8)); ?></span></label></dt>
			<dd class="twelve last columns"><input type="password" name="password" id="password" minlength="5" maxlength="100" autocomplete="off" value="">
			<small><?php echo __(Kohana::message('admin','leave password empty'));?></small></dd>

			<dt class="eight columns"><label><?php echo ucfirst(__('confirm password'));?></label></dt>
			<dd class="twelve last columns"><input type="password" name="password_confirm" minlength="5" maxlength="100" value=""></dd>

			</dl>


		<div class="button bar">
			<?php echo html::anchor($referrer, __('go back'), array('class' => 'nice button')); ?>
			<button type="submit" class="nice primary button"><?php echo KillerAdmin::spriteImg('save');?><?php echo __('save'); ?></button>
		</div>
	</form>
</div>
