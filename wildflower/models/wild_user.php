<?php
/**
 * WildUser model
 * 
 * Users are Wildflower`s administrator accounts.
 *
 * @todo Allow login to have chars like _.
 * @package wildflower
 */
class WildUser extends AppModel {
	
	var $name = 'WildUser';
	var $belongsTo = array('WildGroup');
	var $actsAs = array('Acl' => array('requester'));
	
	public $hasMany = array(
	    'WildPage',
	    'WildPost',
	);

    public $validate = array(
        'name' => array(
            'rule' => array('between', 1, 255),
            'allowEmpty' => false,
            'required' => true
        ),
		'login' => array(
			'rule' => array('alphaNumeric', array('between', 5, 50)),
			'required' => true,
			'message' => 'Login must be between 5 to 50 alphanumeric characters long'
		),
		'password' => array(
            'between' => array(
                'rule' => array('between', 5, 50),
                'required' => true,
                'message' => 'Password must be between 5 to 50 characters long'
            ),
            'confirmPassword' => array(
                'rule' => array('confirmPassword'),
                'message' => 'Please enter the same value for both password fields'
            )
        ),
		'email' => array(
			'rule' => 'email',
			'required' => true,
			'message' => 'Please enter a valid email address'
		)
    );

    /**
     * Does password and password confirm match?
     *
     * @return bool true
     */
    function confirmPassword() {
        App::import('Security');
        $confirmPassword = $this->data[$this->name]['confirm_password'];
        $confirmPassword = Security::hash($confirmPassword, null, true);
        if ($confirmPassword !== $this->data[$this->name]['password']) {
            return false;
        }
        // if (!isset($this->data[$this->name]['id'])) {
        //     $this->data[$this->name]['password'] = $confirmPassword;
        // }
        return true;
    }
    
    function parentNode() {
	if (!$this->id && empty($this->data)) {
		return null;
	}
	$data = $this->data;
	if (empty($this->data)) {
		$data = $this->read();
	}
	if (!$data['User']['wild_group_id']) {
		return null;
	} else {
		return array('Group' => array('id' => $data['User']['wild_group_id']));
	}
    }
}
