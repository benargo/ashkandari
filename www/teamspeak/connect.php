<?php

/* Start by including the framework */
require_once('../../framework/config.php');

/* Check if the user is logged in */
if(isset($_SESSION['account'])) {
	
	/* Get their account and primary character */
	$account = new account($_SESSION['account']);
	$character = $account->getPrimaryCharacter();
	
	/* Now connect them */
	header("Location: ts3server://ashkandari.com/?port=9987&nickname=". $character->name);
	
} else {
	
	header("Location: https://ashkandari.com/account/login?ref=/teamspeak/connect");
	
}

?>