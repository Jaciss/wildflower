<div class="Groups view">
<h2><?php  __('Group');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $Group['Group']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $Group['Group']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $Group['Group']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $Group['Group']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Group', true), array('action'=>'edit', $Group['Group']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Group', true), array('action'=>'delete', $Group['Group']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $Group['Group']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Groups', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Group', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List  Users', true), array('controller'=> '_users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New  User', true), array('controller'=> '_users', 'action'=>'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related  Users');?></h3>
	<?php if (!empty($Group['User'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Login'); ?></th>
		<th><?php __('Password'); ?></th>
		<th><?php __('Email'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __(' Group Id'); ?></th>
		<th><?php __('Cookie Token'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Updated'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($Group['User'] as $User):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $User['id'];?></td>
			<td><?php echo $User['login'];?></td>
			<td><?php echo $User['password'];?></td>
			<td><?php echo $User['email'];?></td>
			<td><?php echo $User['name'];?></td>
			<td><?php echo $User['_group_id'];?></td>
			<td><?php echo $User['cookie_token'];?></td>
			<td><?php echo $User['created'];?></td>
			<td><?php echo $User['updated'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> '_users', 'action'=>'view', $User['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> '_users', 'action'=>'edit', $User['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> '_users', 'action'=>'delete', $User['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $User['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New  User', true), array('controller'=> '_users', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
