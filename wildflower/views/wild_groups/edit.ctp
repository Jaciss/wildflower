<div class="wildGroups form">
<?php echo $form->create('WildGroup');?>
	<fieldset>
 		<legend><?php __('Edit WildGroup');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('WildGroup.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('WildGroup.id'))); ?></li>
		<li><?php echo $html->link(__('List WildGroups', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Wild Users', true), array('controller'=> 'wild_users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Wild User', true), array('controller'=> 'wild_users', 'action'=>'add')); ?> </li>
	</ul>
</div>
