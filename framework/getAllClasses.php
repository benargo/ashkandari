<?php /* getAllClasses.php */

// This file is executed and gets a cache of all the game's Classes
// And adds them to our database

// So the first thing to do, is include the configuration details
require_once('../db/mysqli_connection.php');

$file = file_get_contents("http://eu.battle.net/api/wow/data/character/classes");

// Is the returned JSON file valid?
if($file) {
	
	// Create a new object with the decoded JSON
	$json = json_decode($file);
	
} else {
	
	die("Failed to return a valid JSON file");
	
}

?><!DOCTYPE html>
<html><head><title>getAllClasses.php</title></head><body>
<h1>getAllClasses.php</h1>
<?php

// Loop through each realm
foreach($json->classes as $class) {
	
	$db->query("INSERT INTO `conf_classes` (`name`) VALUES ('$class->name')");
	echo "<p>". $class->name ." &#10003;</p>";

}
?><p><strong>Adding Classes Completed!</strong></p></body></html>