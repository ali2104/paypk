<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

//$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$name = 'PayPak';
$siteDescritpion = $name.' : Easiest Payment Solution';
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<style>
.float_right {
	clear:none;
	float:right;
}
</style>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $siteDescritpion ?>:
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		
		//Bootstrap CSS
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('custom-bootstrap');
		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	
	<!--
	Calling $this->Html->script('...'); doesnt work (maybe some cakephp error since it is putting src="paypak/js/...." 
	when it should put (as in doc)  src="js/...." )
	-->
	
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo $this->base; ?>/js/jquery-1.11.0.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo $this->base; ?>/js/bootstrap.min.js"></script>
	
	
</head>
<body>
	<div id="container">
		<!--<div id="header">
			<h1><?php echo $siteDescritpion; ?></h1>
			
			<form class="float_right" style= "clear:none;" name="login_form" method="post" action="users/signup" id="login_form">
				<input type="text" name="username" value="" placeholder="Mobile Number" id="s-user"/>
						
				<input type="password" name="password" value=""  placeholder="Password" id="s-pass"/>
				
				<input type="submit" name="login" value="Log In" style="font-size:18px; " />
				
			</form>
			
		</div>-->
		
		 <nav class="navbar navbar-default header">
		  <div class="container-fluid">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="<?php echo $this->webroot; ?>"><span class="glyphicon glyphicon-lock"></span> <?php echo $siteDescritpion; ?></a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
			  <ul class="nav navbar-nav navbar-right">
				<?php if (!$loggedIn): ?>
				<li><a href="<?php echo $this->base; ?>/users/signup"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
				<li><a href="<?php echo $this->base; ?>/users/login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
				<?php else: ?>
				<p class="navbar-text">Signed in as <a href="#" class="navbar-link">Mark Otto</a></p>
				<li><a href="<?php echo $this->base; ?>/users/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
				<?php endif; ?>
			  </ul>
			</div>
		  </div>
		</nav>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		
		</div>
	</div>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>
