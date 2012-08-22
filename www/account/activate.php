<?php # account/activate.php

/* 
 * This page accepts the account ID and activation code and activates it against the database.
 */
 
// Switch HTTPS on
if( empty($_SERVER['HTTPS']) ) {
	header('Location: https://www.ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the head of the page
require_once('../../framework/config.php');

// Check if we're already logged in
if(isset($_SESSION['account'])) {
	
		header("Location: /account/");
	
}

/* Set the page title */
$page_title = "Account Activation";

/* Inclide the header */
require(PATH.'framework/head.php');

/* Create a new account from the provided ID. */
$tmp_account = new account($_GET['id']);

/* Activate the account */
$activated = $tmp_account->activate($_GET['code']);

/* Run the activation function */
switch( $activated ) {
	
	case "success":
		/* If it's dropped into here it means that the account has been properly activated
		 * So the next step is to get the primary character from the account we just activated */
		
		?><h1>Account Activated</h1>
		
		<p>Thank you very much for activating your account. You're now good to go. For your convenience we've included a login form below so that you can log straight into your new account.</p>
		
		<form action="/account/login?ref=/" method="post">
		
			<p><input type="email" name="email" required="true" placeholder="Email Address" /></p>
			<p><input type="password" name="password" required="true" /></p>
			<p><input type="submit" value="Login" /></p>
			
		</form><?php
		break;
	
	case "new_password_required":
		/* If it's dropped into here it means that the account has been properly activated
		 * However the user needs to set a new password in order to continue, so that's what we will do here */
		
		?><h1>New Password Required</h1>
		
		<p>Thank you very much. Your account is activated but in order to continue we need you to set a new password for your account.</p>
		
		<form action="/account/password/change" method="post">
			
			<input type="hidden" name="account_id" value="<?php echo $tmp_account->id; ?>" />
			
			<p>Please type a new password in the box below:</p>
			<p><input type="password" name="password" required="true" placeholder="Password" /></p>
			
			<p>We now need you to retype that password for verification purposes:</p>
			<p><input type="password" name="password_verify" required="true" placeholder="Retype Password" /></p>
			
			<p><input type="submit" value="Change Password" /></p>
			
		</form><?php
		break;
	
	case false:
		/* If it's dropped into here it means that we've been unable to activate the account */
		?><h1>Activation Failed</h1>
		
		<p>We had a small problem with our activation procedures, so unfortuantely we're not able to fully activate your account. If you speak to an officer in-game they should be able to verify you manually.</p><?php
		break;
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>