<script>
$(document).ready(function(){
	$("#User").submit(function(e) {
		e.preventDefault();
		$('#VerficationModal').modal('show');
		initiateText();
	});
	
	
	$("#VerificationForm").submit(function(e) {
		e.preventDefault();
		verify();
	});
	
	$('#send_again').on('click',function (e){
		e.preventDefault();
		
		initiateText();
	});
	
});

function initiateText() {

	//Grey out the background and show the loading icon
	$(".modal-content").css("opacity", 0.4);
	$("#loading-img").css({"display": "block"});
   
	$.ajax({
		dataType: "json",
		type: "POST",
		evalScripts: true,
		url: '<?php echo Router::url(array('controller'=>'users','action'=>'text'));?>',
		data: ({mobile_number: $('#UserUsername').val()}),
		success: function (data, textStatus){
			
			console.log('ajax call status: '+ textStatus);
			console.log(data.message);
			
			$(".modal-content").css("opacity", 1);
			$("#loading-img").css({"display": "none"});
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			alert("Status: " + textStatus); alert("Error: " + errorThrown); 
			console.log(errorThrown);
		} 
	});
}


function verify(){
	$.ajax({
		dataType: "json",
		type: "POST",
		evalScripts: true,
		url: '<?php echo Router::url(array('controller'=>'users','action'=>'verify'));?>',
		data: ({code: $('#verification_code').val(), mobile_number: $('#UserUsername').val()}),
		success: function (data, textStatus){
			//alert('test');
			//alert('Data: '+data);
			console.log('ajax call status: '+ textStatus);
			console.log(data.message);
			if (data.status)
			{
				$('#verification_message').show();
				$('#verification_message').removeClass('alert-danger').addClass('alert-success');
				$('#verification_message div').text(data.message);
				$('#verification_message div').append(' Click <a href="<?php echo Router::url(array('controller'=>'users','action'=>'login'));?>">here</a> to Login');
				
				signup();
			}
			else
			{
				$('#verification_message').show();
				$('#verification_message').removeClass('alert-success').addClass('alert-danger');
				$('#verification_message div').text(data.message);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			alert("Status: " + textStatus); alert("Error: " + errorThrown); 
		} 
	});

function signup(){
	$.ajax({
		dataType: "json",
		type: "POST",
		evalScripts: true,
		url: '<?php echo Router::url(array('controller'=>'users','action'=>'signup'));?>',
		data: $("#User").serialize(),
		success: function (data, textStatus){
			//alert('test');
			//alert('Data: '+data);
			console.log('ajax call status: '+ textStatus);
			console.log(data);
			
			/*if (data.status)
			{
				$('#verification_message').show();
				$('#verification_message').removeClass('alert-danger').addClass('alert-success');
				$('#verification_message div').text(data.message);
				$('#verification_message div').append(' Click <a href="<?php echo Router::url(array('controller'=>'users','action'=>'login'));?>">here</a> to Login');
				
				signup();
			}
			else
			{
				$('#verification_message').show();
				$('#verification_message').removeClass('alert-success').addClass('alert-danger');
				$('#verification_message div').text(data.message);
			}*/
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			alert("Status: " + textStatus); alert("Error: " + errorThrown); 
		} 
	});
}

}
</script>
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

/* Loading icon */
.glyphicon.spinning {
    animation: spin 1s infinite linear;
    -webkit-animation: spin2 1s infinite linear;
}

@keyframes spin {
    from { transform: scale(1) rotate(0deg); }
    to { transform: scale(1) rotate(360deg); }
}

@-webkit-keyframes spin2 {
    from { -webkit-transform: rotate(0deg); }
    to { -webkit-transform: rotate(360deg); }
}
.glyphicon-refresh.spinning {
	background-color: #00CCFF;
}
#loading-img {
	background-color: #00CCFF;
	border:#00CCFF;
	position:absolute;
	top : 125px;
	left: 275px;
	display:none;
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

<!-- Modal -->
<div id="VerficationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
	
	<!-- Modal Loading img-->
	<button class="btn btn-lg btn-warning" id="loading-img">
		<span class="glyphicon glyphicon-refresh spinning"></span>
	</button>
		
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Verification Code</h4>
      </div>
      <div class="modal-body">
	  <p>A verification code has been sent to your mobile phone, please enter the code to verify your account.</p>
	  <div id="clock"></div>

		<!-- Error message box-->
		<div id="verification_message" style="display:none;" class="alert" role="alert">
			<!--<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>-->
			<span class="sr-only">Error:</span>
			<div></div>
		</div>
		
		<!-- Verification form-->
        <form style= "clear:none;" name="verification_form" method="post" id="VerificationForm">
			<input type="text" name="code" value="" placeholder="Enter Verification Code" id="verification_code" maxlength="6"/>
			<br><input type="submit" name="submit" value="Submit" style="font-size:18px; " />
		</form> 	 
		<br>
		<p>Haven't received the code yet? Send <a href="#" id="send_again">again</a></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<?php 
	echo $this->Form->create('User', array('type' => 'post', 'id' => 'User'));
	echo $this->Form->input('name', array('label' => false, 'placeholder' => 'Full Name'));
	echo $this->Form->input('username', array('label' => false, 'placeholder' => 'Mobile Number'));
	echo $this->Form->input('password', array('label' => false, 'placeholder' => 'Password'));
	echo $this->Form->submit('Sign Up For Free');
	echo $this->Form->end();
?>




