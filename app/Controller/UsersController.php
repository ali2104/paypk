<?php
/**
 *	Controller to add/view/edit users etc.
 */

App::uses('Controller', 'Controller');

class UsersController extends AppController {
	
	
	public function beforeFilter() {
		parent::beforeFilter();
		// Allow users to register and logout. (and to be able to receive texts and verify code)
		$this->Auth->allow('signup', 'logout', 'text', 'verify');
	}

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				debug('logged in');
				return $this->redirect($this->Auth->redirectUrl());
			}
			debug('Invalid username or password, try again');
			$this->Session->setFlash('Invalid username or password, try again', 'default', array(), 'bad');
		}
	}

	public function logout() {
		return $this->redirect($this->Auth->logout());
	}
	
	
	public function signup()
	{
		// Has any form data been POSTed?
		if ($this->request->is('ajax')) {
		
			$this->autoRender = false;
			
			//Prepare return data 
			$return_data = array('message'=>'');
			
			//Check that the user is verified before adding the user to the database
			$this->loadModel('Verification');
			
			$verified = $this->Verification->find('first', array('username'=>$this->request->data['User']['username'], 'verified'=>1));
			
			if ($verified)
			{
				//Set the data to the model
				$this->User->set($this->request->data);
				
				//Check if the data validates
				if ($this->User->validates())
				{	
					// If the form data can be validated and saved...
					if ($this->User->save($this->request->data)) {
						// Set a session flash message and redirect.
						//$this->Session->setFlash('Recipe Saved!');		
						
						//return $this->redirect('../');
						$return_data['message'] = 'User created';
					}
				}
				else $return_data['message'] = 'Error! User not created';
				//Remove 'else' later on
				//else $this->request->data['User']['password'] = '';
			}
			
			echo json_encode($return_data);
		}
		
	}
	
	/*
	* Send the user a randomly generated code 
	* and save the code in the database
	* AJAX only function
	*/
	public function text()
	{
		if( !$this->request->is('ajax') ) {
			$this->redirect('../');
		}
		$this->autoRender = false;
		
		//Prepare return data
		$return_data = array('status'=>false, 'number'=>'', 'message' =>'');
		
		// Change 033630724646 to +923363072464 (international/twilio format)
		$number = substr_replace($this->request->data['mobile_number'], '+92', 0, 1);
		$return_data['number'] = $number;
		
		//Generate a random code to text the user
		$code = rand(100000, 999999);
		
		// this line loads the library 
		require('/twilio-php/Services/Twilio.php'); 
	
		//Keep these two details safe
		$account_sid = 'AC0a1998e262b78dcc07b23df3afb77732'; 
		$auth_token = '03c637acb70f30d7af3f2b6fdb5cb41f'; 
		
		$client = new Services_Twilio($account_sid, $auth_token); 
		
		try {		
			$message = $client->account->messages->create(array( 
				'To' => $number, 
				'From' => "+12093538729", 
				'Body' => "Your PayPK verification code is: ".$code,   
			));
			$return_data['status'] = true;
			$return_data['message'] = "Sent message";
			
		} catch (Exception $e) {
			$return_data['status'] = false;
			$return_data['message'] = 'Error sending verfication code: ' . $e->getMessage();
		}

		//Save the verification code generated in the database if the message has been sent
		if ($return_data['status'])
		{
			//Save that code against the mobile_number of the user
			$verfication_data = array('mobile_number' => $this->request->data['mobile_number'], 'code' => $code);
			$this->loadModel('Verification');
			//$this->Verification->deleteAll(array('Verification.mobile_number' => $verfication_data['mobile_number']), false);
			$this->Verification->save($verfication_data);
		}
		
		
		echo json_encode($return_data);
	}
	
	
	/*
	* Verify the code user entered against the random code generated,
	* and return the result of the verification
	* AJAX only function
	*/
	public function verify()
	{
		if( !$this->request->is('ajax') ) {
			$this->redirect('../');
		}
		$this->autoRender = false;
		
		//Prepare return data
		$return_data = array('status'=>false, 'message' =>'');
		
		$number = $this->request->data['mobile_number'];
		$code = $this->request->data['code'];
		
		//$number = '03363072464';
		//$code = '731823';
		
		$this->loadModel('Verification');
		
		//Verify the code entered against the `verifications` table in the db
		$find_data = $this->Verification->find('first', array('conditions' => array(
			'Verification.mobile_number'=>$number, 
			'Verification.code'=>$code,
			'now() between Verification.created and Verification.expiry' 
			)
		));
		
		if ($find_data)
		{
			//Save that the user has been verified
			$find_data['Verification']['verified'] = true;
			$this->Verification->save($find_data);
			
			//Save return data
			$return_data['status'] = true;
			$return_data['message'] = 'Success! Your mobile number has been verified!';
		}
		else
		{
			//Save return data
			$return_data['status'] = false;
			$return_data['message'] = 'Error! The code you entered is incorrect or no longer valid. Please try again.';
		}
		
		echo json_encode($return_data);
	}
	
	/*
	* Pay the specified recipient the amount specified by the sender
	* and return the status of the process
	* AJAX only function
	*/
	public function pay()
	{
		if( !$this->request->is('ajax') ) {
			$this->redirect('../');
		}
		$this->autoRender = false;
		
		//Prepare return data
		$return_data = array('status'=>false, 'message' =>'');
		
		if (!$recipient = $this->User->find('first', array( 
			'conditions' => array(
				'User.username'=>$this->request->data['Transaction']['username'], 
				'User.id NOT '=>$this->Auth->user('id')
			), 
			'fields' => array('id', 'balance'))))
		{
			$return_data['message'] = 'Error! The recipient specified does not exist';
			echo json_encode($return_data);
			return;
		}
		
		$sender = $this->User->findById($this->Auth->user('id'), 'balance');
		
		if ($this->request->data['Transaction']['amount'] > $sender['User']['balance'])
		{
			$return_data['message'] = 'Error! You do not have the funds to send the amount specified';
			echo json_encode($return_data);
			return;	
		}
		
		
		$transaction = array('recipient_id'=>$recipient['User']['id'], 'sender_id'=>$this->Auth->user('id'), 'amount'=>$this->request->data['Transaction']['amount']);
		$this->loadModel('Transaction');
		
		if ($this->Transaction->save($transaction))
		{
			//Since transaction has gone through, save the balances of the sender and recipient
			$this->User->id = $transaction['recipient_id'];
			$this->User->saveField('balance', $recipient['User']['balance']+$transaction['amount']);
			$this->User->id = $transaction['sender_id'];
			$this->User->saveField('balance', $sender['User']['balance']-$transaction['amount']);
			
			//Save return data
			$return_data['status'] = true;
			$return_data['message'] = 'Success! Your payment has been sent!';
		}
		else
		{
			//Save return data
			$return_data['status'] = false;
			$return_data['message'] = 'Error! Something went wrong. Payment not sent';
		}
		
		echo json_encode($return_data);
	
	}
	
	
	
}
