<a name="list"></a>
<form method="get">
	<table>
		<tr>
			<td><input type="text" name="filter[username]" style="width: 100%" value="<?= (isset($filter['username']) ? htmlentities($filter['username']) : '');?>"></td>
			<td><input type="text" name="filter[email]" style="width: 100%" value="<?= (isset($filter['email']) ? htmlentities($filter['email']) : '');?>"></td>
			<td><button><?= html::image('admin/media/images/icons/funnel.png');?><?= __('filter'); ?></button>
				<?= (isset($_GET['filter']) ? html::anchor('admin/' . Request::instance()->controller, __('clear filter')) : "");?>
			</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<th>Naam</th>
			<th>Email</th>
			<th>Rollen</th>
			<th>Laatste login</th>
			<th>&nbsp;</th>
		</tr>
		<?php foreach ($objects as $object) : ?>
		<tr>
			<td><?= $object->username; ?></td>	
			<td><?= $object->email; ?></td>	
			<td><?php foreach ($object->roles->find_all() as $role) :?>
				<?= $role->name; ?>			
			<?php endforeach; ?></td>
			<td><?= ($object->last_login ? strftime("%R %a %e %b %G", $object->last_login) : '-'); ?></td>	
			<td nowrap="nowrap">
				<?= html::anchor( $controller_url . '/edit/' . $object->id, html::image('admin/media/images/icons/pencil.png', array('title' => __('edit')))); ?> 
				<?= ($object->id != $auth_user->id) ? html::anchor('admin/users/delete/' . $object->id, html::image('admin/media/images/icons/bin.png', array('title' => __('delete'))), array('class' => 'delete')) : ""; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</form>
<div class="span-8">
	<a href="<?= url::site('admin/users/add');?>" class="button positive"><?=html::image('admin/media/images/icons/add.png');?><?= __('add :object', array(':object' => __('user'))); ?></a>
</div>

<div class="span-26 last pagination">
	<?= $pagination;?>
</div>