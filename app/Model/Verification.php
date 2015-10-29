<?php
/**
 * Verification model for database table `verifications`
*/

class Verification extends AppModel {

	var $name = 'Verification';

	
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['created'])) {
		
			//Set expiry of the code to 5mins after being created
			$this->data[$this->alias]['expiry'] = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime($this->data[$this->alias]['created'])));
		}
		return true;
	}
}
