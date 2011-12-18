<a name="list"></a>
<form method="get" class="table">
	<div class="filter row">
		<div class="four columns"><?php echo Killeradmin::filterField("username", $filter); ?></div>
		<div class="seven columns"><?php echo Killeradmin::filterField("email", $filter); ?></div>
		<div class="twelve columns last"><?php echo Killeradmin::filterButton(); ?></div>
	</div>
	<div class="header row">
		<div class="four columns"><?php echo ucfirst(__('username')); ?>&nbsp;<?php echo Killeradmin::sortAnchor('username'); ?></div>
		<div class="seven columns"><?php echo ucfirst(__('email')); ?>&nbsp;<?php echo Killeradmin::sortAnchor('email'); ?></div>
		<div class="four columns"><?php echo ucfirst(__('rols')); ?>&nbsp;</div>
		<div class="six columns last"><?php echo ucfirst(__('last login')); ?>&nbsp;<?php echo Killeradmin::sortAnchor('last_login');?></div>
	</div>
	<?php $i = 0; foreach ($objects as $object) :?>
		<div class="row <?php echo Text::alternate('odd', 'even');?>" id="<?php echo $i++; ?>">
			<div class="four columns"><?php echo $object->username; ?></div>	
			<div class="seven columns"><?php echo $object->email; ?></div>	
			<div class="four columns">
				<?php foreach ($object->roles->find_all() as $role) :?>
					<?php echo $role->name; ?>
				<?php endforeach; ?>
			</div>
			<div class="six columns nowrap"><?php echo ($object->last_login ? strftime("%R %a %e %b %G", $object->last_login) : '-'); ?></div>	
			<div class="one tools columns last">
				<?php echo html::anchor( $controller_url . '/edit/' . $object->id, KillerAdmin::spriteImg('pencil', __('edit') )); ?> 
				<?php echo ($object->id != $auth_user->id) ? html::anchor($controller_url . '/delete/' . $object->id, KillerAdmin::spriteImg('bin', __('delete')), array('class' => 'delete')) : ""; ?>
			</div>
		</div>
	<?php endforeach; ?>
</form>

<?php echo Killeradmin::newButton('user'); ?>

<div class="span-26 last pagination">
	<?php echo $pagination;?>
</div>
