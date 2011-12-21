<div class="twelve columns prefix-6">
	<form method="post" class="validate" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller'=>'settings','action'=>'save')));?>">
        <label><?php echo ucfirst(__('username'));?></label>
		<span class="info"><?php echo $user->username;?></span>

        <label><?php echo ucfirst(__('email'));?></label>
      	<input type="text" name="email" class="required email" value="<?php echo $user->email; ?>">
       
        <label><?php echo ucfirst(__('password'));?> <span><?php echo __(':min-:max characters', array(':min' => 5, ':max' => 8)); ?></span></label>
        <input type="password" name="password" id="password" minlength="5" maxlength="100" value="">
        <small><?php echo __(Kohana::message('admin','leave password empty'));?></small>

        <label><?php echo ucfirst(__('confirm password'));?></label>
        <input type="password" name="password_confirm" minlength="5" maxlength="100" value="">
		
		<div class="button bar">
       		<?php echo html::anchor($referrer, __('go back'), array('class' => 'button')); ?>
			<button type="submit" class="button primary"><?php echo KillerAdmin::spriteImg('save');?><?php echo __('save'); ?></button>
		</div>
	</form>
</div>
