<style>
#User {
	margin-top: 50px;
	margin-left: 50px;
	width:350px;
}
#User input {
	margin:0;
	color:#000;
	font-size: 120%;
    width: 100%;
    border: 1px solid #eee;
    padding: 3px 0 3px 0;
}
#User div {
	margin:0;
	padding:1px;
}
#User .submit input[type="submit"] {
	border:none;
	margin-top: 30px;
	background: #00CCFF;
	color: #fff;
}
#User .submit input[type="submit"]:hover {
	cursor: pointer;
	background: #842121;
	color: #fff;
}

/** Headers
*/
#description {
	clear:none;
	float:left;
}
#description h2{
	color: #00CCFF;
}
#description h1 {
	color: #00CCFF;
	font-size: 35px;
}
</style>

<!--
<div>
	
	<form style= "clear:none;" name="signup_form" method="post" action="signup.php" id="signup_form">
		<input type="text" name="first_name" value="" placeholder="First Name"/>
		<input type="text" name="last_name" value="" placeholder="Last Name"/>
				
		<input type="email" name="email" value=""  placeholder="Password"/>
		<input type="password" name="password" value=""  placeholder="Password"/>
		
		<input type="submit" name="login" value="Log In" style="font-size:18px; " />
	</form> 	 
</div>
-->
<!--

<div class="users form">
<?php //echo $this->Flash->render('auth'); ?>
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend>
            <?php echo __('Please enter your username and password'); ?>
        </legend>
        <?php echo $this->Form->input('username');
        echo $this->Form->input('password');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>
-->
<?php 

	echo $this->Form->create('User', array('type' => 'post', 'id' => 'User'));
	echo $this->Form->input('username', array('label' => false, 'placeholder' => 'Mobile Number'));
	echo $this->Form->input('password', array('label' => false, 'placeholder' => 'Password'));
	echo $this->Form->submit('Log In');
	echo $this->Form->end();
?>

