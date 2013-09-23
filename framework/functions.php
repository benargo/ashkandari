	<?php
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
	if($race = $result->fetch_object()) {
		// Free result set
		$result->close();
			
		// Close database connection
		$db->close();
			
		// And return the object
		return $race;
	}

	return false;
	
		
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
?>