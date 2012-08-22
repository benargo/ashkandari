<?php

// Require the head of the page
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: /account/login?ref=/officers/epgp/");
	
}

/* Get the new raw EPGP */
$raw_epgp = $_POST['epgp'];

/* Decode the JSON */
$epgp = json_decode($raw_epgp);

/* Now go through each of the characters */
foreach($epgp->roster as $entry) {
	
	/* Get the character ID */
	$character_id = character::getCharacterByName($entry[0]);
	
	/* Create the character object */
	$character = new character($character_id);
	
	/* Set the EP */
	$character->setEP($entry[1]);
	
	/* Set the GP */
	$character->setGP($entry[2]);
	
}

/* Return to the thread */
header("Location: /officers/");

?>