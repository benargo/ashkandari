<?php // account/forum/signature-save.php

/* 
 * This updates a user's forum signature
 */
 
// Switch HTTPS on
if( empty($_SERVER['HTTPS']) ) {
	header('Location: https://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
		header("Location: https://ashkandari.com/account/login?ref=/account/forum/signature");
	
}

$account = new account($_SESSION['account']);

if($account->setSignature($_POST['signature'])) {
	
	$_SESSION['account_msg'] = "Your forum signature has been updated";
	$_SESSION['account_msg_class'] = "success";
	header("Location: /account/");
	
} else {
	
	$_SESSION['account_msg'] = "Failed to update your forum signature";
	$_SESSION['account_msg_class'] = "error";
	header("Location: /account/");
	
}



?>