<div class="wildGroups form">
<?php echo $form->create('WildGroup');?>
	<fieldset>
 		<legend><?php __('Add WildGroup');?></legend>
	<?php
		echo $form->input('name');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List WildGroups', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Wild Users', true), array('controller'=> 'wild_users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Wild User', true), array('controller'=> 'wild_users', 'action'=>'add')); ?> </li>
	</ul>
</div>
