<?php /* Ashkandari Framework */

/***************************************************
 * This is the primary configuration file for
 * Ashkandari's web application. It provides a
 * number of items, such as constant items, database
 * configuration and the classes for object oriented
 * interaction.
 *
 * Table of Contents
 * -----------------
 * 1. Pre-flight checks
 * 2. Constants
 * 3. Database integration
 * 4. Configuration functions
 * 5. Account class
 ***************************************************/

/* 1. Pre-flight checks */
// Start by turning sessions on
session_start();

// Determine the protocol (HTTP or HTTPS)
if( empty($_SERVER["HTTPS"]) ) {
	$protocol = "http";
} else {
	$protocol = "https";
}

/* 2. Constants */
define("PATH", "/home/ashkandari/"); // Base path for this server
define("BASE_URL", $protocol."://ashkandari.com"); // The URL this web application runs off

/* Salt integration - This is in a seperate file for security reasons */
require_once(PATH."framework/salt.php");

/* 3. Database integration
 * For this we need to include the database connection details.
 * This is in a seperate file for security reasons :P */
require_once(PATH."db/mysqli_connection.php");

// Declare the database function
function db() {
	
	/* Create a new instance of our database
	 * NB: This function returns an OBJECT */

	// First, get the variables from the included file
	global $db_details;

	// Create the new MySQLi object
	$db = new mysqli($db_details["host"], $db_details["user"], $db_details["password"], $db_details["name"]);

	// And return it for our use.
	return $db;
		
}

/* 4. Configuration functions */
function getAllCountries() {
	
	$db = db();
	
	$result = $db->query("SELECT * FROM `countries` ORDER BY `name`");
	
	$db->close();
	
	return $result;
	
}

function getAllRealms() {

	/* Get all the realms from the database
	 * This function returns a RESULT SET */
		
	// Set up a new instance of the database
		$db = db();
		
	// Run the query to select all realms
	$result = $db->query("SELECT * FROM `realms` ORDER BY `name`");
								
	// Close database connection
	$db->close();
			
	// And return the result set
	return $result;
		
}

function getRealm($realm_id = 201) {

	/* Get a specific realm, identified by its index (`id`)
	 * The default realm is 201 - which is the ID for "Tarren Mill"
	 * This function returns an OBJECT */

	// Set up a new instance of the database
	$db = db();
		
	// Run the query to select the realm
	$result = $db->query("SELECT * FROM `realms` WHERE `id` = $realm_id LIMIT 0, 1");
			
	// Fetch an array of the row
	$realm = $result->fetch_object();
			
	// Free result set
	$result->close();

	// Close database connection
	$db->close();
		
	// And return the array
	return $realm;

}
	
function getAllClasses() {
		
	/* Get all the classes from the database
	 * This function returns a RESULT SET */
	 
	// Set up a new instance of the database
	$db = db();
		
	// Run the query to select the classes
	$result = $db->query("SELECT * FROM `classes` ORDER BY `name`");
			
	// Close database connection
	$db->close();
		
	// And return the object
	return $result;
		
}
	
function getClass($class_id) {

    /* Get a specific class, identified by its index (`id`)
	 * This function returns an OBJECT */
		
	// Set up a new instance of the database
	$db = db();
		
	// Run the query to select the class
	$result = $db->query("SELECT * FROM `classes` WHERE `id` = $class_id LIMIT 0, 1");
			
	// Fetch an object of the row
	$class = $result->fetch_object();
			
	// Free result set
	$result->close();
		
	// Close database connection
	$db->close();
		
	// And return the object
	return $class;
		
}
	
function getClassBySlug($class_slug) {
		
	/* Get a specific class, identified by its unique slug (`slug`)
	 * This function returns an OBJECT */
	
	// Set up a new instance of the database
	$db = db();
		
	// Run the query to select the class
	$result = $db->query("SELECT * FROM `classes` WHERE `slug` = '$class_slug' LIMIT 0, 1");
			
	// Fetch an object of the row
	$class = $result->fetch_object();
			
	// Free result set
	$result->close();
		
	// Close database connection
	$db->close();
		
	// And return the object
	return $class;
		
}
	
function getAllRaces() {
		
	/* Get all the races from the database
	 * This function returns a RESULT SET */	
	
	// Set up a new instance of the database
	$db = db();
		
	// Run the query to select the races
	$result = $db->query("SELECT * FROM `races` ORDER BY `faction`, `name`");
			
	// Close database connection
	$db->close();
		
	// And return the object
	return $result;

}
	
function getRacesByFaction($faction = "horde") {
		
	/* Get all the races of a specific faction from the database
	 * The default faction is Horde (as Ashkandari is a Horde guild)
     * This function returns a RESULT SET */
	
	// Set up a new instance of the database
	$db = db();
		
	// Run the query to select the races
	$result = $db->query("SELECT * FROM `races` WHERE `faction` = '$faction' ORDER BY `name`");
			
	// Close database connection
	$db->close();
		
	// And return the object
	return $result;

}
	
function getRace($race_id) {

	/* Get a specific race from the database by its index (`id`)
	 * This function returns an OBJECT */
		
	// Set up a new instance of the database
	$db = db();
		
	// Run the query to select the race
	$result = $db->query("SELECT * FROM `races` WHERE `id` = $race_id LIMIT 0, 1");
			
	// Fetch an object of the above query
	$race = $result->fetch_object();
			
	// Free result set
	$result->close();
		
	// Close database connection
	$db->close();
		
	// And return the object
	return $race;
		
}
	
function getRaceBySlug($race_slug) {

	/* Gets a specific race from the database by its unique slug (`slug`)
	 * This function returns an OBJECT */
		
	// Set up a new instance of the database
	$db = db();
		
	// Run the query to select the race
	$result = $db->query("SELECT * FROM `races` WHERE `slug` = '$race_slug' LIMIT 0, 1");
			
	// Fetch an object of the above query
	$race = $result->fetch_object();
			
	// Free result set
	$result->close();
		
	// Close database connection
	$db->close();
		
	// And return the object
	return $race;

}
	
function getAllRanks() {
		
	/* This function gets all the available ranks from the database
	 * This function returns a RESULT SET */
		
	// Set up a new instance of the database
	$db = db();

	// Query the database
	$result = $db->query("SELECT * FROM `guild_ranks` ORDER BY `id`");

	// Close the database connection
			$db->close();
			
	// Return the result set 
	return $result;

}

function getRankBySlug($rank_slug) {
	
	/* This function gets a specific rank based on its unique slug (`slug`)
	 * This function returns an OBJECT */
		
	// Set up a new instance of the database
	$db = db();
		
	// Run the query to select the race
	$result = $db->query("SELECT * FROM `guild_ranks` WHERE `slug` = '$rank_slug' LIMIT 0, 1");
			
	// Fetch an object of the above query
	$rank = $result->fetch_object();
			
	// Free result set
	$result->close();
		
	// Close database connection
	$db->close();
		
	// And return the object
	return $rank;
		
}
	
function getItemSlot($slot_id) {
		
	/* This function checks the database for a specific item slot, based on the criteria required
     * This function returns an OBJECT */
		
	// Include the database
	$db = db();
		
	// Query the database to get the slot
	$result = $db->query("SELECT * FROM `item_slots` WHERE `id` = $slot_id LIMIT 0, 1");
			
	// Create an object from the result set
	$obj = $result->fetch_object();
			
	// Free the result set
	$result->close();
			
	// Close the database connection
	$db->close();
			
	// Return the object
	return $obj;
		
}

function validate_character($character_name) {
	
	return true;
	
}
	
function getRandomItemSlot($not = "offHand") {
	
	/* This function checks the database for a random item slots, based on the criteria required
	 * This function returns an OBJECT */
		
	// Include the database
	$db = db();
		
	$result = $db->query("SELECT * FROM `item_slots` WHERE `used_for_verification` = 1 AND `id` <> '$not' ORDER BY RAND() LIMIT 0, 1");
			
	// Create an object from the result set
	$obj = $result->fetch_object();
			
	// Free the result set
	$result->close();
			
	// Close the database connection
	$db->close();
			
	// Return the object
	return $obj;
	
} 

function encrypt($text) 
{ 
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
} 

function decrypt($text) 
{ 
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
} 

/* 5. Account class */
require_once(PATH.'framework/account.class.php');

/* 6. Character */
require_once(PATH.'framework/character.class.php');

/* 7. News class */
require_once(PATH.'framework/news_item.class.php');
require_once(PATH.'framework/news_comment.class.php');

/* 8. Guild Applications */
require_once(PATH.'framework/applications.class.php');

?>