<form method="post" action="<?= url::site('admin/users/save/' . $user->id);?>">
    <div class="span-6 suffix-1">
        <label><?= ucfirst(__('username'));?> <small>(<?= __(':min-:max characters', array(':min' => 5, ':max' => 8)); ?>)</small></label>
        	<input type="text" name="username" value="<?= $user->username;?>"><br>
        <label><?= ucfirst(__('email'));?></label>
        	<input type="text" name="email" value="<?= $user->email; ?>"><br>
        <label><?= ucfirst(__('roles'));?></label>
        <?php 
            foreach (ORM::factory('role')->find_all() as $role) {
            	$checked = $user->has('roles',$role ) ? "checked=checked" : "";
            	$disabled = ($user->id == $auth_user->id) ? "disabled=disabled" : "";
            	echo "<input type=\"checkbox\" name=\"role[".$role->name."]\" $checked $disabled>" . $role->name . "<br>";
            
            }
            
        ?>
        
    </div>

    <div class="span-5 last">
        <label><?= ucfirst(__('password'));?> <small>(<?= __(':min-:max characters', array(':min' => 5, ':max' => 8)); ?>)</small></label> <input type="password" name="password" value="">
        <label><?= ucfirst(__('confirm password'));?>:</label> <input type="password" name="password_confirm" value="">
    </div>
	
   	<div class="span-24">
   		<p><br>
       		<button type="submit" class="button positive"><?= html::image('admin/media/images/icons/save.png');?><?= __('save'); ?></button>
       		<?= html::anchor($referrer, __('go back')); ?>
   		</p>
   	</div>
</form>
