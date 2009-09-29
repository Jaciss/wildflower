<div class="Groups form">
<?php echo $form->create('Group');?>
	<fieldset>
 		<legend><?php __('Add Group');?></legend>
	<?php
		echo $form->input('name');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Groups', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List  Users', true), array('controller'=> '_users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New  User', true), array('controller'=> '_users', 'action'=>'add')); ?> </li>
	</ul>
</div>
