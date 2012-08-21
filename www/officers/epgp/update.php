<?php

// Require the head of the page
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=/officers/epgp/");
	
}

/* Get the new raw EPGP */
$raw_epgp = $_POST['epgp'];

/* Decode the JSON */
$epgp = json_decode($raw_epgp);

/* And print it out raw */
print_r($epgp);
exit;

/* Return to the thread */
header("Location: /officers/");

?>