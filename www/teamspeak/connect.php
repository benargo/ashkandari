<?php

/* Start by including the framework */
require_once('../../framework/config.php');

/* Check if the user is logged in */
if(isset($_SESSION['account'])) {
	
	/* Get their account and primary character */
	$account = new account($_SESSION['account']);
	$character = $account->getPrimaryCharacter();
	
	/* Require the teamspeak tokens */
	require_once(PATH.'framework/teamspeak/tokens.php');
	
	/* Now connect them */
	header("Location: ts3server://ashkandari.com/?port=9987&amp;nickname=". $character->name ."&amp;token=$token&amp;addbookmark=1");
	
} else {
	
	header("Location: https://ashkandari.com/account/login?ref=/teamspeak/connect");
	
}

?>