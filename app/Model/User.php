<?php
/**
 * User model for database table `users`
*/
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');


class User extends AppModel {

	var $name = 'User';
	var $displayField = 'name';
	
	
	public $validate = array(
        'name' => array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'required' => true,
                'message' => 'Letters and numbers only'
            ),
            'between' => array(
                'rule' => array('lengthBetween', 5, 100),
                'message' => 'Between 5 to 100 characters'
            ),
        ),
        'username' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'required' => true,
                'message' => 'Numbers only'
            ),
            'minLength' => array(
                'rule' => array('minLength', '11'),
				'message' => 'Minimum 11 characters long'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
				'message' => 'The mobile number already exists. Please choose another'
            ),
        ),
        'password' => array(
            'rule' => array('minLength', '8'),
            'message' => 'Minimum 8 characters long'
        ),
    );
	
	/*
	public $hasMany = array(
        'SentTransaction' => array(
            'className' => 'Transaction',
            'conditions' => array('Transaction.sender_id' => 'User.id'),
            'order' => 'Transaction.created DESC'
        ),
		'ReceivedTransaction' => array(
            'className' => 'Transaction',
            'conditions' => array('Transaction.recipient_id' => 'User.id'),
            'order' => 'Transaction.created DESC'
        )
    );*/
	
	
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash(
				$this->data[$this->alias]['password']
			);
		}
		return true;
	}
}
