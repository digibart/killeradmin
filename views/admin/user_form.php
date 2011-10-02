<form method="post" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller' => 'users', 'action' => 'save', 'id' =>  $user->id)));?>">
    <div class="span-6 suffix-1">
        <label><?php echo ucfirst(__('username'));?> <small>(<?php echo __(':min-:max characters', array(':min' => 5, ':max' => 8)); ?>)</small></label>
        	<input type="text" name="username" value="<?php echo $user->username;?>"><br>
        <label><?php echo ucfirst(__('email'));?></label>
        	<input type="text" name="email" value="<?php echo $user->email; ?>"><br>
        <label><?php echo ucfirst(__('roles'));?></label>
        <?php 
            foreach (ORM::factory('role')->find_all() as $role) {
            	$checked = $user->has('roles',$role ) ? "checked=checked" : "";
            	$disabled = ($user->id == $auth_user->id) ? "disabled=disabled" : "";
            	echo "<input type=\"checkbox\" name=\"role[".$role->name."]\" $checked $disabled>" . $role->name . "<br>";
            
            }
            
        ?>
        
    </div>

    <div class="span-5 last">
        <label><?php echo ucfirst(__('password'));?> <small>(<?php echo __(':min-:max characters', array(':min' => 8, ':max' => 100)); ?>)</small></label> <input type="password" name="password" value="">
        <label><?php echo ucfirst(__('confirm password'));?>:</label> <input type="password" name="password_confirm" value="">
    </div>
	
   	<div class="span-24">
   		<p><br>
       		<button type="submit" class="button positive"><?php echo KillerAdmin::spriteImg('save');?><?php echo __('save'); ?></button>
       		<?php echo html::anchor($referrer, __('go back')); ?>
   		</p>
   	</div>
</form>
