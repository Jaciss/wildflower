<div class="wildGroups view">
<h2><?php  __('WildGroup');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $wildGroup['WildGroup']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $wildGroup['WildGroup']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $wildGroup['WildGroup']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $wildGroup['WildGroup']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit WildGroup', true), array('action'=>'edit', $wildGroup['WildGroup']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete WildGroup', true), array('action'=>'delete', $wildGroup['WildGroup']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $wildGroup['WildGroup']['id'])); ?> </li>
		<li><?php echo $html->link(__('List WildGroups', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New WildGroup', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Wild Users', true), array('controller'=> 'wild_users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Wild User', true), array('controller'=> 'wild_users', 'action'=>'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Wild Users');?></h3>
	<?php if (!empty($wildGroup['WildUser'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Login'); ?></th>
		<th><?php __('Password'); ?></th>
		<th><?php __('Email'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('Wild Group Id'); ?></th>
		<th><?php __('Cookie Token'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Updated'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($wildGroup['WildUser'] as $wildUser):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $wildUser['id'];?></td>
			<td><?php echo $wildUser['login'];?></td>
			<td><?php echo $wildUser['password'];?></td>
			<td><?php echo $wildUser['email'];?></td>
			<td><?php echo $wildUser['name'];?></td>
			<td><?php echo $wildUser['wild_group_id'];?></td>
			<td><?php echo $wildUser['cookie_token'];?></td>
			<td><?php echo $wildUser['created'];?></td>
			<td><?php echo $wildUser['updated'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'wild_users', 'action'=>'view', $wildUser['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'wild_users', 'action'=>'edit', $wildUser['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'wild_users', 'action'=>'delete', $wildUser['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $wildUser['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Wild User', true), array('controller'=> 'wild_users', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
