<div class="six columns prefix-three last">
	<form method="post" class="validate" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller' => 'users', 'action' => 'save', 'id' =>  $user->id)));?>">
		<label for="username"><?php echo ucfirst(__('username'));?> <span><?php echo __(':min-:max characters', array(':min' => 5, ':max' => 8)); ?></span></label>
		<input type="text" name="username" id="username" class="required" minlength="5" maxlength="8" value="<?php echo $user->username;?>"><br>
	    
		<label for="email"><?php echo ucfirst(__('email'));?></label>
	    <input type="text" name="email" id="email" class="required email" value="<?php echo $user->email; ?>">
	        
		<label class="spacer"><?php echo ucfirst(__('roles'));?></label>
	    <div><?php 
	            foreach (ORM::factory('role')->find_all() as $role) {
	            	$checked = $user->has('roles',$role ) ? "checked=checked" : "";
	            	$disabled = ($user->id == $auth_user->id) ? "disabled=disabled" : "";
	            	echo "<input type=\"checkbox\" name=\"role[".$role->name."]\" $checked $disabled>" . $role->name . "<br>";
	            
	            }
	            
	        ?>
	    </div>
	         	
        <label><?php echo ucfirst(__('password'));?> <span><?php echo __(':min-:max characters', array(':min' => 8, ':max' => 100)); ?></span></label>
        <input type="password" name="password" minlength="8" maxlength="100" value="">
        
        <label><?php echo ucfirst(__('confirm password'));?>:</label>
        <input type="password" name="password_confirm" value="" minlength="8" maxlength="100">
		
	   	<div class="button bar">
	       		<?php echo html::anchor($referrer, __('go back'), array('class' => 'button')); ?>
	       		<button type="submit" class="button primary"><?php echo KillerAdmin::spriteImg('save');?><?php echo __('save'); ?></button>
	   		
	   	</div>
	</form>
</div>
