<?php
class WildGroup extends AppModel {

	var $name = 'WildGroup';
	var $validate = array(
		'name' => array('alphanumeric')
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
			'WildUser' => array('className' => 'WildUser',
								'foreignKey' => 'wild_group_id',
								'dependent' => false,
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			)
	);

	var $actsAs = array('Acl' => array('requester'));
	
	function parentNode() {
		return null;
	}

}
?>