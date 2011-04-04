<form method="post" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller'=>'settings','action'=>'save')));?>">
    <div class="span-6 suffix-1">
        <label><?php echo ucfirst(__('username'));?> </label>
        	<?php echo $user->username;?><br>
        <label><?php echo ucfirst(__('email'));?></label>
        	<input type="text" name="email" value="<?php echo $user->email; ?>"><br>
        
    </div>

    <div class="span-15 last">
        <label><?php echo ucfirst(__('password'));?> <small>(<?php echo __(':min-:max characters', array(':min' => 5, ':max' => 8)); ?>)</small></label> <input type="password" name="password" value="">
        <small><?php echo html::image(Route::get('admin/media')->uri(array('file'=>'/images/icons/information.png')), array('style' => 'padding:0 5px;')); ?><?php echo __(Kohana::message('admin','leave password empty'));?></small>

        <label><?php echo ucfirst(__('confirm password'));?>:</label> <input type="password" name="password_confirm" value="">
    </div>
	
   	<div class="span-24">
   		<p><br>
       		<button type="submit" class="button positive"><?php echo html::image(Route::get('admin/media')->uri(array('file'=>'/images/icons/save.png')));?><?php echo __('save'); ?></button>
       		<?php echo html::anchor($referrer, __('go back')); ?>
   		</p>
   	</div>
</form>
