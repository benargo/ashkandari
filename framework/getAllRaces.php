<?php /* getAllRaces.php */

// This file is executed and gets a cache of all the game's Races
// And adds them to our database

// So the first thing to do, is include the configuration details
require_once('../db/mysqli_connection.php');

$file = file_get_contents("http://eu.battle.net/api/wow/data/character/races");

// Is the returned JSON file valid?
if($file) {
	
	// Create a new object with the decoded JSON
	$json = json_decode($file);
	
} else {
	
	die("Failed to return a valid JSON file");
	
}

?><!DOCTYPE html>
<html><head><title>getAllRaces.php</title></head><body>
<h1>getAllRaces.php</h1>
<?php

// Loop through each realm
foreach($json->races as $race) {
	
	$db->query("INSERT INTO `conf_races` (`faction`, `name`) VALUES ('$race->side', '$race->name')");
	echo "<p>". $race->name ." &#10003;</p>";

}
?><p><strong>Adding Races Completed!</strong></p></body></html>