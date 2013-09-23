<?php // account/password/change.php

/* 
 * This is the password change page. Here logged in users can either update their password
 * Or it can be used as an acceptance form for those required to set a new password
 */
 


	
}

// Require the framework files
require_once('../../../framework/config.php');

// Set the page title
$page_title = "Change your Password";

// Require the head of the document
require(PATH.'framework/head.php');

// Check if we have post variables.
if( isset($_POST['account_id'], $_POST['password'], $_POST['password_verify']) ) {

	/* If it's dropped into here it means that we have been provided with $_POST
	 * variables, and as a result we should run the setPassword function */
	 
	if($_POST['password'] == $_POST['password_verify']) {
	
		if(empty($account)) {
		
			$account = new account($_POST['account_id']);
			$primary_character = $account->getPrimaryCharacter();
		
		}
		
		/* If it's dropped into here it means that we've been able to create an account based on the given information
		 * So now we can reset the password, and send the activation email */
		
		$account->setPassword($_POST['password']);
		
		?><h1>Password Changed</h1>
		
		<p class="success">Thank you <?php echo $primary_character->name; ?>. You have succesfully changed your password.</p><?php
		
		if($_SESSION['account']) {
			
			?><p><a href="/account/">Return to your account</a></p><?php
			
		} else {
			
			?><p>You can now login to your account using your email address and new password. To help you do this we have included a login form for you below.</p>
			
			<form action="/account/login?ref=/account/" method="post">
				
				<p>Email:</p>
				<p><input type="email" name="email" required="true" placeholder="Email Address" /></p>
				
				<p>Password:</p>
				<p><input type="password" name="password" required="true" placeholder="Password" /></p>
				
				<p><input type="submit" value="Login" /></p>
				
				<p class="text right"><a href="/account/password/reset" title="Reset your password">Forgotten your password already?</a></p>
			
			</form><?php
			
		}
		
	} else {
		
		?><h1>Change your password</h1>
		
		<p class="error">Unfortunately the two passwords you typed didn't match, so we need you to type them again.</p>
		
		<form action="/account/password/change" method="post">
		
			<input type="hidden" name="account_id" value="<?php echo $account->id; ?>" />
		
			<p>Type a new password:</p>
			<p><input type="password" name="password" required="true" placeholder="Password" /></p>
			
			<p>And just type it again for verification:</p>
			<p><input type="password" name="password_verify" required="true" placeholder="Retype Password" /></p>
			
			<p><input type="submit" value="Change Password" /></p>
		
		</form><?php
		
	}
			
} else {
	
	/* Okay they obviously need the change form them
	 * But first check that they are actually logged in */
	if(isset($account, $primary_character)) {
		
		/* Okay they must be logged in */
		?><h1>Change your password</h1>
		
		<p>Hi <?php echo $primary_character->name; ?>.</p>
		
		<p>Want to change your password? Not a problem! Just fill out the form below and we'll have you sorted out in no time.</p>
		
		<form action="/account/password/change" method="post">
		
			<input type="hidden" name="account_id" value="<?php echo $account->id; ?>" />
		
			<p>Type a new password:</p>
			<p><input type="password" name="password" required="true" placeholder="Password" /></p>
			
			<p>And just type it again for verification:</p>
			<p><input type="password" name="password_verify" required="true" placeholder="Retype Password" /></p>
			
			<p><input type="submit" value="Change Password" /></p>
		
		</form><?php
		
	} else {
		
		header("HTTP/1.0 401 Unauthorized", true, 401);
		
	}
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>