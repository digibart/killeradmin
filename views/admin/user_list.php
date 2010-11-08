<a name="list"></a>
<form method="get">
	<table>
		<tr>
			<td><input type="text" name="filter[username]" class="span-5 last" value="<?php echo (isset($filter['username']) ? htmlentities($filter['username']) : '');?>"></td>
			<td><input type="text" name="filter[email]" class="span-3 last" value="<?php echo (isset($filter['email']) ? htmlentities($filter['email']) : '');?>"></td>
			<td colspan="3"><button type="submit"><?php echo html::image('admin/media/images/icons/funnel.png');?><?php echo __('filter'); ?></button>
				<?php echo (isset($_GET['filter']) ? html::anchor('admin/' . Request::instance()->controller, __('clear filter')) : "");?>
			</td>
		</tr>
		<tr>
			<th class="span-5">Naam</th>
			<th class="span-3">Email</th>
			<th>Rollen</th>
			<th>Laatste login</th>
			<th>&nbsp;</th>
		</tr>
		<?php foreach ($objects as $object) : ?>
		<tr>
			<td><?php echo $object->username; ?></td>	
			<td><?php echo $object->email; ?></td>	
			<td><?php foreach ($object->roles->find_all() as $role) :?>
				<?php echo $role->name; ?>			
			<?php endforeach; ?></td>
			<td><?php echo ($object->last_login ? strftime("%R %a %e %b %G", $object->last_login) : '-'); ?></td>	
			<td nowrap="nowrap">
				<?php echo html::anchor( $controller_url . '/edit/' . $object->id, html::image('admin/media/images/icons/pencil.png', array('title' => __('edit')))); ?> 
				<?php echo ($object->id != $auth_user->id) ? html::anchor('admin/users/delete/' . $object->id, html::image('admin/media/images/icons/bin.png', array('title' => __('delete'))), array('class' => 'delete')) : ""; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</form>
<div class="span-8">
	<a href="<?php echo url::site('admin/users/add');?>" class="button positive"><?php echo html::image('admin/media/images/icons/add.png');?><?php echo __('add :object', array(':object' => __('user'))); ?></a>
</div>

<div class="span-26 last pagination">
	<?php echo $pagination;?>
</div>