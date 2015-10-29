<?php
/**
 * Transaction model for database table `Transactions`
*/


class Transaction extends AppModel {

	var $name = 'Transaction';
	//var $displayField = 'name';
	
	public $belongsTo = array(
        'Recipient' => array(
            'className' => 'User',
            'foreignKey' => 'recipient_id',
			'fields' => 'name'
        ),
        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'sender_id',
			'fields' => 'name'
        )
    );
	
	
	
	/*
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash(
				$this->data[$this->alias]['password']
			);
		}
		return true;
	}
	
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash(
				$this->data[$this->alias]['password']
			);
		}
		return true;
	}*/
}
