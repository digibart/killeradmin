<div class="last twelve columns prefix-6">
	<form method="post" class="validate" action="<?php echo url::site(Route::get('admin/base_url')->uri(array('controller' => 'users', 'action' => 'save', 'id' =>  $user->id)));?>">
		<dl>
			<dt class="twelve columns"><label for="username"><?php echo ucfirst(__('username'));?> <span><?php echo __(':min-:max characters', array(':min' => 5, ':max' => 8)); ?></span></label></dt>
			<dd class="twelve last columns"><input type="text" name="username" id="username" class="required" minlength="5" maxlength="8" autocomplete="off" value="<?php echo $user->username;?>"></dd>
		    
			<dt class="twelve columns"><label for="email"><?php echo ucfirst(__('email'));?></label></dt>
		    <dd class="twelve last columns"><input type="text" name="email" id="email" class="required email" autocomplete="off" value="<?php echo $user->email; ?>"></dd>
		        
			<dt class="twelve columns"><label class="spacer"><?php echo ucfirst(__('roles'));?></label></dt>
		    <dd class="twelve last columns"><div><?php 
		            foreach (ORM::factory('role')->find_all() as $role) {
		            	$checked = $user->has('roles',$role ) ? "checked=checked" : "";
		            	$disabled = ($user->id == $auth_user->id) ? "disabled=disabled" : "";
		            	echo "<input type=\"checkbox\" name=\"role[".$role->name."]\" $checked $disabled>" . $role->name . "<br>";
		            
		            }
		            
		        ?>
		    </div></dd>
		         	
	        <dt class="twelve columns"><label><?php echo ucfirst(__('password'));?> <span><?php echo __(':min-:max characters', array(':min' => 8, ':max' => 100)); ?></span></label></dt>
	        <dd class="twelve last columns"><input type="password" name="password" minlength="8" maxlength="100" autocomplete="off" value=""></dd>
	        
	        <dt class="twelve columns"><label><?php echo ucfirst(__('confirm password'));?>:</label></dt>
	        <dd class="twelve last columns"><input type="password" name="password_confirm" value="" minlength="8" maxlength="100" autocomplete="off"></dd>
		
			
			<dt class="twelve columns"><?php echo html::anchor($referrer, __('go back'), array('class' => 'nice button')); ?></dt>
	       	<dd class="twelve columns last"><button type="submit" class="nice primary button"><?php echo KillerAdmin::spriteImg('save');?><?php echo __('save'); ?></button></dd>
   		</dl>
	</form>
</div>
