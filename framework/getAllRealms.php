<?php /* getAllRealms.php */

// This file is executed and gets a cache of all the European realms
// And adds them to our database

// So the first thing to do, is include the configuration details
require_once('../db/mysqli_connection.php');

$file = file_get_contents("http://eu.battle.net/api/wow/realm/status");

// Is the returned JSON file valid?
if($file) {
	
	// Create a new object with the decoded JSON
	$json = json_decode($file);
	
} else {
	
	die("Failed to return a valid JSON file");
	
}

?><!DOCTYPE html>
<html><head><title>getAllRealms.php</title></head><body>
<h1>getAllRealms.php</h1>
<?php

// Loop through each realm
foreach($json->realms as $realm) {
	
	$db->query("INSERT INTO `conf_realms` (`name`, `slug`) VALUES ('$realm->name', '$realm->slug')");
	echo "<p>". $realm->name ." &#10003;</p>";

}
?><p><strong>Adding Realms Completed!</strong></p></body></html>