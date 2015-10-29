<?php
/**
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

App::uses('Debugger', 'Utility');
?>
<?php
if (Configure::read('debug') > 0):
	Debugger::checkSecurityKeys();
endif;
?>
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
<style>
#Transaction {
	margin-top: 0px;
	margin-left: 0px;
	width:350px;
}
#Transaction input {
	margin:0;
	color:#000;
	font-size: 120%;
    width: 100%;
    border: 1px solid #eee;
    padding: 3px 0 3px 0;
}
#Transaction div {
	margin:0;
	padding:1px;
}
#Transaction .submit input[type="submit"] {
	border:none;
	margin-top: 30px;
	background: #00CCFF;
	color: #fff;
}
#Transaction .submit input[type="submit"]:hover {
	cursor: pointer;
	background: #842121;
	color: #fff;
}
</style>
<!--
If a logged in Session exists, then show the account's contents
-->

<script>
$(document).ready(function(){
	console.log('test');
	$("#Transaction").submit(function(e) {
		e.preventDefault();
		var r = confirm("Are you sure you want to send payment to: "+$('#TransactionUsername').val());
		if (r == true) {
			sendPayment();
		}
	});
	
});

function sendPayment()
{
	$.ajax({
		dataType: "json",
		type: "POST",
		evalScripts: true,
		url: '<?php echo Router::url(array('controller'=>'users','action'=>'pay'));?>',
		data: $("#Transaction").serialize(),
		success: function (data, textStatus){
			//alert('test');
			//alert('Data: '+data);
			console.log('ajax call status: '+ textStatus);
			console.log(data);
			
			if (data.status)
			{
				//Payment has gone through, notify both parties using the notification system(maybe by text as well)
				alert(data.message);
				window.location.replace("<?php echo Router::url(array('controller'=>'users','action'=>'pay'));?>");
			}
			else
			{
				$('#verification_message').show();
				$('#verification_message div').text(data.message);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			alert("Status: " + textStatus); alert("Error: " + errorThrown); 
		} 
	});

}
</script>

<div style="border:2px solid #00CCFF; margin: 100px 100px;">
	<ul class="nav nav-pills">
	  <li class="active"><a data-toggle="tab" href="#n1">Summary</a></li>
	  <li class="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#n2">Pay
		<span class="caret"></span></a>
		<ul class="dropdown-menu">
			<li><a data-toggle="tab" href="#n21">P2P</a></li>
			<li><a data-toggle="tab" href="#n22">Mobile Recharge</a></li>
			<li><a data-toggle="tab" href="#n23">Utility Payments</a></li>
			<li><a data-toggle="tab" href="#n24">Outstanding Invoices</a></li>
		</ul>
	  </li>
	  <li><a data-toggle="tab" href="#n3">Request Payment</a></li>
	  <li><a data-toggle="tab" href="#n4">Top Up</a></li>
	  <li><a data-toggle="tab" href="#n5">Withdraw</a></li>
	</ul>

	<div class="tab-content">
		
		
		<div id="n1" class="tab-pane fade in active">
			<h3>Summary</h3>
			<p>Your recent transcations:</p>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
					<tr>
						<?php 
							echo "<th>" . $this->Paginator->sort('created', 'Date') . "</th>";
						?>
						<th>Descrption</th>
						<th>Amount</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($transactions as $transaction): ?>
					<tr>
						<td><?php echo $transaction['Transaction']['created']?></td>
						<?php if ($user_id == $transaction['Transaction']['sender_id']): ?>
						<td><?php echo 'Sent to '.$transaction['Recipient']['name']?></td>
						<?php else: ?>
						<td><?php echo 'Received from '.$transaction['Sender']['name']?></td>
						<?php endif; ?>
						<?php if ($user_id == $transaction['Transaction']['sender_id']): ?>
						<td><?php echo '- '.$transaction['Transaction']['amount']?></td>
						<?php else: ?>
						<td><?php echo $transaction['Transaction']['amount']?></td>
						<?php endif; ?>
					</tr>
					<?php endforeach;?>
					</tbody>
				</table>
				<?php
					// pagination section
					echo "<div class='paging'>";
				 
						// the 'first' page button
						echo $this->Paginator->first("First");
						 
						// 'prev' page button, 
						// we can check using the paginator hasPrev() method if there's a previous page
						// save with the 'next' page button
						if($this->Paginator->hasPrev()){
							echo $this->Paginator->prev("Prev");
						}
						 
						// the 'number' page buttons
						echo $this->Paginator->numbers(array('modulus' => 2));
						 
						// for the 'next' button
						if($this->Paginator->hasNext()){
							echo $this->Paginator->next("Next");
						}
						 
						// the 'last' page button
						echo $this->Paginator->last("Last");
					 
					echo "</div>";
				?>
			</div>
		</div>
			
			
			<div id="n21" class="tab-pane fade">
				<h3>P2P</h3>
				<div id="verification_message" style="display:none;" class="alert alert-danger fade in" role="alert">
					<!--<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>-->
					<span class="sr-only">Error:</span>
					<div></div>
				</div>
				<?php 	echo $this->Form->create('Transaction', array('type' => 'post', 'id' => 'Transaction'));
						echo $this->Form->input('username', array('label' => false, 'placeholder' => 'To (Mobile Number)'));
						echo $this->Form->input('amount', array('label' => false, 'placeholder' => 'Amount'));
						echo $this->Form->submit('Pay');
						echo $this->Form->end();
				?>
			</div>
			
			<div id="n22" class="tab-pane fade">
				<h3>Mobile Recharge</h3>
				<p>Some content in menu 1.</p>
			</div>
			<div id="n23" class="tab-pane fade">
				<h3>Utility Payments</h3>
				<p>Some content in menu 1.</p>
			</div>
			<div id="n24" class="tab-pane fade">
				<h3>Outstanding Invoices</h3>
				<p>Some content in menu 1.</p>
			</div>
		<div id="n3" class="tab-pane fade">
			<h3>Request Payment</h3>
			<p>Some content in menu 1.</p>
		</div>
		<div id="n4" class="tab-pane fade">
			<h3>Top Up</h3>
			<p>Some content in menu 2.</p>
		</div>
		<div id="n5" class="tab-pane fade">
			<h3>Withdraw</h3>
			<p>Some content in menu 2.</p>
		</div>
	</div>




	<div style="width:auto;" >
	<p>Your PayPak balance is: <b>Rs. <?php echo $balance?> /- </b></p>
	</div>

	
	
	
	
	
</div>




<!--
<div id="description">
<h1>Why use Game Trade?</h1>

<h1>How it works?</h1>
<h2>1. Easy Sign up </h2>
<h2>2. List your collection</h2>
<h2>3. Choose from available games from others' collection</h2>
<h2>4. Trade!</h2>
</div>-->





