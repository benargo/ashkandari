<?php // account/email/set-preferences.php

/* 
 * This page accepts the preferences filled out on the previous page, updates them in the database and then redirects back to the account page.
 */
 


	
}

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
		header("Location: /account/login?ref=/account/email/");
	
}

$account = new account($_SESSION['account']);

if(isset($_POST['news'])) {
	
	$news = 1;
	
} else {
	
	$news = 0;
	
}

if(isset($_POST['digest'])) {
	
	$digest = 1;
	
} else {
	
	$digest = 0;
	
}

if($account->setEmailPreferences($news, $digest)) {
	
	$_SESSION['account_msg'] = "Your email preferences have been updated";
	$_SESSION['account_msg_class'] = 'success';
	header("Location: /account/");
	
}

else {
	
	$_SESSION['account_msg'] = "Unable to update your email preferences";
	$_SESSION['account_msg_class'] = 'error';
	header("Location: /account/");
	
}