<?php
class UserImage extends AppModel {
	var $name = 'UserImage';
	var $displayField = 'title';
	var $actsAs = array(
		'MeioUpload.MeioUpload' => array('filename')
	);
	var $validate = array(
		'user_profile_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'UserProfile' => array(
			'className' => 'UserProfile',
			'foreignKey' => 'user_profile_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
