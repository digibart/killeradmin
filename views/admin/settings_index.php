<div class="twelve columns prefix-6">
<form method="post" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller'=>'settings','action'=>'save')));?>">
        <label class="twelve columns"><?php echo ucfirst(__('username'));?> </label>
		<p><?php echo $user->username;?></p>

        <label><?php echo ucfirst(__('email'));?></label>
      	<input type="text" name="email" value="<?php echo $user->email; ?>">
       
        <label style="height: 85px;"><?php echo ucfirst(__('password'));?> <span><?php echo __(':min-:max characters', array(':min' => 5, ':max' => 8)); ?></span></label>
        <input type="password" name="password" value=""><br>
        <small><?php echo KillerAdmin::spriteImg('info'); ?><?php echo __(Kohana::message('admin','leave password empty'));?></small>

        <label><?php echo ucfirst(__('confirm password'));?></label>
        <input type="password" name="password_confirm" value="">

   		<p><br>
       		<button type="submit" class="button positive"><?php echo KillerAdmin::spriteImg('save');?><?php echo __('save'); ?></button>
       		<?php echo html::anchor($referrer, __('go back')); ?>
   		</p>
</form>
</div>
