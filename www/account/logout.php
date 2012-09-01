<?php // account/logout.php

/* 
 * This is the page which allows users to logout from the Ashkandari website
 */

// Require the framework files
require_once('../../framework/config.php');

unset($_SESSION['account']);
setcookie('account', "", 1, '/');

if(isset($_GET['ref'])) {
		
	header("Location: ". $_GET['ref']);
	
} else {

	header("Location: ". BASE_URL);
	
}