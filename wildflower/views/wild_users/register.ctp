<h2>Registration</h2>
<p>Please fill out the form completely.</p>
<?php

echo $form->create('WildUser', array('url' => '/users/register')),
    $form->input('name', array('between' => '<br />', 'tabindex' => '1')),
    $form->input('email', array('between' => '<br />', 'tabindex' => '2')),
    $form->input('login', array('between' => '<br />', 'tabindex' => '3')),
    $form->input('passwrd', array('between' => '<br />', 'tabindex' => '4', 'type' => 'password', 'label' => 'Password')),
    $form->input('confirm_password', array('between' => '<br />', 'tabindex' => '5', 'type' => 'password', 'label' => 'Confirm Password')),
    $form->submit('Create Account'),
    $form->end();
?>