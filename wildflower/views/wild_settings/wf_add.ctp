<h2 class="section">Add Setting</h2>
<?php
echo $form->create('WildSetting',array('url' => $html->url(array('action' => 'wf_add', 'base' => false)))),
     $form->input('name'),
     $form->input('value'),
     $form->input('description'),
     $form->input('type', array(
        'type' => 'select', 
        'options' => array('general' => 'General', 'theme' => 'Theme')
     )),
     $form->end('Add');