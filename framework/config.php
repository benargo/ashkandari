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

/* 5. Account class */
class account {
	
	/* This class is the basis for the entire operation
	 * It is the parent class for many other classes, and performs a large number of functions
	 * Accessing user information
	 * Authenticating Users
	 * Managing access levels and permissions
	 * Linking up to characters
	 * And tracing content */
	
	/* Variables
	 * This class has a wide number of variables attached to it
	 * and are laid out below */
	public $id;
	public $email;
	private $password;
	public $activiation_code;
	private $active;
	private $suspended;
	private $forum_signature;
	private $forum_moderator;
	public $primary_character;
	
	/* Construction */
	public function __construct($account_id, $email = false) {
	
		/* This function will set most of the variables above based on a user lookup 
	 	 * It is most likely to be called during an authentication */
		
		// First, set up a new instance of the database
		$db = db();
		
		if($email == true) {
			
			// Override the account ID
			$account_id = $this->getAccountIdFromEmail($account_id);
			
		}

		// Query the database to see if we can find this particular user
		$result = $db->query("SELECT * FROM `accounts` WHERE `id` = $account_id LIMIT 0, 1");
			
		// Fetch an object based on the result
		$account = $result->fetch_object();
			
		// Free result set
		$result->close();
		
		// Now we can start setting the variables
		$this->id = $account->id;
		$this->email = $account->email;
		$this->password = $account->password;
		$this->activation_code = $account->activation_code;
		$this->active = $account->active;
		$this->forum_signature = $account->forum_signature;
		$this->forum_moderator = $account->forum_moderator;
		$this->primary_character = $account->primary_character;
		
		// Close the database connection
		$db->close();
		
		// And return true
		return true;
		
	}
	
	/* Lookup account by email address */
	private function getAccountIdFromEmail($email) {
	
		/* This PRIVATE function allows us to look up an account ID for further use within the class purely by supplying an email address.
	 	 * It does not return anything else about the account */
		
		// Set up a new instance of the database
		$db = db();
		
		// Query the database to see if we can find the account
		$result = $db->query("SELECT `id` FROM `accounts` WHERE `email` = '$email' LIMIT 0, 1");
			
		// Create an object of the result
		$obj = $result->fetch_object();
			
		// Free result set
		$result->close();

		// Close the database connection
		$db->close();
		
		// Return the account ID
		return $obj->id;
		
	}
	
	/* Authentication */
	public static function authenticate($email, $raw_password) {
		
		/* This STATIC function will authenticate users against the database and return one of three strings
	 	 * which can be switched through to determine the following action */
		
		// First, set up a new instance of the database
		$db = db();
		
		// Next, lookup the account ID from the email address given
		$id = self::getAccountIdFromEmail($email);
		
		// Set up the password by encrypting it
		$password = md5($raw_password);
		
		// Query the database to find a user with the specified email address and password
		$result = $db->query("SELECT `id`, `active`, `suspended` FROM `accounts` WHERE `id` = $id AND `password` = '$password' LIMIT 0, 1");
			
		// If it's dropped into here it means that we can find an account with the correct credentials
		// However, we now need to check if it's been properly activated and it's not suspended
		
		// First though, set the result to an instance of an OBJECT
		if($account = @$result->fetch_object()) {
			
			// Free result set
			$result->close();
			
			// Close the database connection
			$db->close();
			
			// Perform the suspended & active check
			if( $account->suspended == 0 && $account->active == 1 ) {	
			
    			// If we've got into here then it means that the account is properly active and not suspsended
    			// so we can go ahead and properly authenticate by returning the account ID
    			
    			return $account->id;
				
			} elseif( $account->suspended == 1 ) {
    			
    			// Oh dear, the account has been suspended.
    			return 'suspended';
    			
			} elseif( $account->active == 0 ) {
    			
    			// Well, this must mean the account is inactive
    			return 'inactive';
    			
			}
			
		} 
		
		// Oh dear, seems that we cannot find the account with the credentials given to us.
	
		// Close the database connection here
		$db->close();
	
		// For security reasons we cannot tell them whether its a problem with their username or password
		// so we just have to throw it back to them saying that it was the wrong error.
		return 'fail';
		
	}

	/* Activate account */
	public function activate($code) {
	
		/* This function takes in an activation code and account ID as provided and successfully activates the account.
	 	 * If the password field is also NULL, as a result of a password reset or this is the first time someone is activating
	 	 * their account after applying to the guild, then it will also prompt them to enter a new password. */
		
		// Set up a new instance of the database
		$db = db();
		
		// Query the database to find a match between the two supplied items
		if( $result = $db->query("SELECT * FROM `accounts` WHERE `id` = ". $this->id ." AND `activation_code` = '$code' LIMIT 0, 1") ) {
			
			// If we've dropped into here it means that we've successfully found an account and the activation code matches.
			
			// Create an object from the result set
			$obj = $result->fetch_object();
			
			// Free the result set
			$result->close();
			
			// Create a new activation code to use next time
			$new_code = md5(time());
			
			// Set the account to active and update the activation code
			if( $db->query("UPDATE `accounts` SET `activation_code` = '$new_code', `active` = 1 WHERE `id` = ". $this->id) ) {
				
				// Now we need to check if they have a NULL password
				if( empty($obj->password) ) {
				
	    			// Close the database connection
	    			$db->close();
					
					// If it's dropped into this section then it means we need to ask them to set a new password
					return "new_password_required";
					
				}
				
				// Close the database connection
				$db->close();
				
				// Return out of this function
				return "success";	
				
			} else {
				
				die($db->error);
				
			}
			
		} else {
		
    		// Oh dear, we weren't able to find an account with that ID or activation code so we have to exit out false.
    		
    		// Close the database connection
			$db->close();
			
			// Return out false
			return false;
    		
		}
		
	}
	
	 /* Officer check */
	 public function isOfficer() {
	 
	 	/* This function will check against the current account object to see if they are an officer or not
	  	 * This helps in user permissions and enables us to display certain content to officers of the guild */
    	
    	// Get the primary character
    	$character = $this->getPrimaryCharacter();
    	$rank = $character->getRank();
    	
    	if($rank->id <= 2 || $rank->id == 5) {
	    	
	    	return true;
	    	
    	}
    	
    	return false;
    	
	}
	
	 /* Moderator check */
	 public function isModerator() {
		 
		/*	This function will check against the current account object to see if they are authentciated as a moderator or not
	  	 * Note that for the purposes of this function, and the seniority of officer status over moderator status, it will match
	 	 * both officer status or moderator status and return true. */
		 
		 if($this->forum_moderator) {
			 
			 // If we've dropped into here then it means that the user is either an officer or a moderator
			 return true;
			 
		 }
		 
		 return false;
		 
	 }
	 
	 public function getPrimaryCharacter() {
		 
		 /* Create a new character object from this account */
		 $character = new character($this->primary_character);
		 return $character;
		 
	 }
	 
	 
	 public function getAllCharacters() {
		 
		 $db = db();
		 
		 $result = $db->query("SELECT `id` FROM `characters` WHERE `account_id` = ". $this->id ." ORDER BY `name`");
		 
		 $db->close();
		 
		 return $result;
		 
	 }
	 
	 public function setPrimaryCharacter($character_id) {
		 
		/* Validate if this character is owned by this account */
		$character = new character($character_id);
		
		/* Check if we own this character */
		if($character->isThisMine($this->id)) {
			
			/* Create a DB */
			$db = db();
			
			/* Update the primary character */
			$db->query("UPDATE `accounts` SET `primary_character` = ". $character->id ." WHERE `id` = ". $this->id);
			
			$db->close();
			
			return true;
			
		}
			
		return false;
		 
	 }
	 
	 public function setPassword($value = NULL) {
		 
		 $db = db();
		 
		 if($value == NULL) {
		 
			 $this->password = $value;
			 $db->query("UPDATE `accounts` SET `password` = NULL, `active` = 0 WHERE `id` = ". $this->id);
			 $db->close();
			 return true;
			 
		 } 
		 
		 $this->password = md5($value);
		 $db->query("UPDATE `accounts` SET `password` = '". md5($value) ."' WHERE `id` = ". $this->id);
		 $db->close();
		 return true;
		 
	 }	 
	
}

/* 6. Character */
class character {
	
	/* This class controls all the characters registered in the database, and the various items about them. */
	
	/* Variables */
	public $id;
	private $account_id;
	public $name;
	private $class;
	private $race;
	private $gender;
	public $level;
	public $achievements;
	private $rank;
	public $thumbnail;
	
	public function __construct($character_id) {
		
		// First, set up a new instance of the database
		$db = db();
		
		// Query the database to see if we can find this particular character
		if( $result = $db->query("SELECT * FROM `characters` WHERE `id` = $character_id LIMIT 0, 1") ) {
			
			// If it dropped into here, fantastic, it means we've been able to find the character and we can continue with the function
			// Create an object from the result set
			$obj = $result->fetch_object();
			
			// Now we can free the result set
			$result->close();
			
			// And close the database connection
			$db->close();
			
			// Now we can start applying the variables
			$this->id = $obj->id;
			$this->account_id = $obj->account_id;
			$this->name = $obj->name;
			$this->class = $obj->class;
			$this->race = $obj->race;
			$this->gender = $obj->gender;
			$this->level = $obj->level;
			$this->achievements = $obj->achievementPoints;
			$this->rank = $obj->rank;
			$this->thumbnail = $obj->thumbnail_url;
		
			// And finally return out true
			return true;
			
		} else {
			
			// Unfortunately we couldn't find such a character
			// so we'll close the database connection
			$db->close();
			
			// And exit out false
			return false;
			
		}
		
	}
	
	public static function getCharacterByName($character_name) {
		
		/* This function gets the ID of a certain character based on a given name
		 * First, set up a new instance of the database */
		$db = db();
		
		/* Query the database to see if we can find this particular character */
		if( $result = $db->query("SELECT `id` FROM `characters` WHERE `name` = '$character_name' LIMIT 0, 1") ) {
			
			// If it dropped into here, fantastic, it means we've been able to find the character and we can continue with the function
			// Create an object from the result set
			$obj = $result->fetch_object();
			
			// Now we can free the result set
			$result->close();
			
			// And close the database connection
			$db->close();
			
			// We can now return a copy of the ID number
			return $obj->id;
			
		} else {
			
			// Unfortunately we couldn't find such a character
			// so we'll close the database connection
			$db->close();
			
			// And exit out false
			return false;
			
		}
		
	}
	
	public static function getAllCharacters() {
		
		/* This function gets all of the guild's characters and returns them as an object
		 * First, set up a new instance of the database */
		$db = db();
		
		/* Query the database to see if we can get them */
		if( $result = $db->query("SELECT * FROM `characters`") ) {
			
			/* If it's dropped into here it means we can return the result set
			 * But start by closing the database connection */
			$db->close();
			
			/* Now return the result set */
			return $result;
			
		} else {
			
			/* Oh dear, something went wrong
			 * So close the database connection */
			$db->close();
			
			/* And return out false */
			return false;
			
		}
		
	}
	
	public function getAccount() {
		
		// Quite simply, we need to return an instance of the account object based on this character
		if(isset($this->account_id)) {
		
			$account = new account($this->account_id);
			
			// And return it out for us to use
			return $account;
			
		}
		
		return false;
		
	}
	
	public function isThisMine($account_id) {
		
				// Quite simply, we need to return an instance of the account object based on this character
		if($this->account_id == $account_id) {
		
			return true;
			
		}
		
		return false;
		
	}
	
	public function getClass() {
		
		// This function gets the class from configuration class
		$class = getClass($this->class);
		return $class;
		
	}
	
	public function getRace() {
		
		// This function gets the race from the config class and returns it for us to use
		$race = getRace($this->race);
		return $race;
		
	}
	
	public function getGender() {
		
		switch($this->gender) {
			
			case 0:
				return 'male';
				break;
			case 1:
				return 'female';
				break;
			
		}
		
	}
	
	public function getRaceIcon() {
		
		$db = db();
		$race = $this->getRace();
		
		switch($this->gender) {
			
			case 0:
				return $race->male_icon;
				break;
				
			case 1:
				return $race->female_icon;
				break;

			
		}
		
	}
	
	public function getRank() {
		
		// This function gets the ranks from the database and allows us to return them as an object
		
		// Start by defining a database connection
		$db = db();
		
		// Run the database query
		if( $result = $db->query("SELECT * FROM `guild_ranks` WHERE `id` = ". $this->rank ." LIMIT 0, 1") ) {
			
			// Fetch an object from the result set
			$obj = $result->fetch_object();
			
			// Free the result set
			$result->close();
			
			// Close the database connection
			$db->close();
			
			// And return the object
			return $obj;
			
		} else {
			
			// Close the database connection
			$db->close();
			
			// And return false
			return false;
			
		}
		
	}
	
	public function getActiveSpec() {
		
		/* Decode this data */
		$bnet_data = $this->getBattleNetData("talents");
		
		/* Run a conditional */
		if( $bnet_data->talents[0]->selected ) {
			
			/* The first spec is selected
			 * Return the name of the first spec */
			return $bnet_data->talents[0]->name;
			
		} elseif( $bnet_data->talents[1]->selected ) {
			
			/* The second spec is selected
			 * Return the name of the second spec */
			return $bnet_data->talents[1]->name;
		} else {
			
			/* Oh dear something's gone wrong */
			return false;
			
		}
		
	}

	public function getOffSpec() {
		
		/* Decode this data */
		$bnet_data = $this->getBattleNetData("talents");
		
		/* Run a conditional */
		if( $bnet_data->talents[0]->selected ) {
			
			/* The first spec is selected
			 * Return the name of the second spec */
			return $bnet_data->talents[1]->name;
			
		} elseif( $bnet_data->talents[1]->selected ) {
			
			/* The second spec is selected
			 * Return the name of the first spec */
			return $bnet_data->talents[0]->name;
		} else {
			
			/* Oh dear something's gone wrong */
			return false;
			
		}

		
	}
	
	public function getCurrentTitle() {
		
		/* This function fetches a list of titles owned by a character from battle.net
		 * Decodes it, and then displays the characters name alongside their currently
		 * selected title */
		 
		/* Get the data from battle.net */
		if ( $bnet_data = $this->getBattleNetData("titles") ) {
		
			/* Loop through each of the titles until we find the one we want */
			foreach($bnet_data->titles as $title) {
				
				if($title->selected) {
					
					/* If it's dropped into here it means we've found it!
					 * Calcualte the name by replacing %s with the actual name */
					$full_title = str_replace("%s", $this->name, $title->name);
					
					/* And return the full title */
					return $full_title;
					
					/* And that's it! Nothing else to do, as it will return false everywhere else */
					
				} 
				
			}
		
		}
		
		/* Well maybe we haven't found a title, so let's just return their name */
		return $this->name;
		
	}
	
	public function getThumbnail() {
		
		/* This function either returns the URL of their thumbnail for normal players
		 * Or, if they're fluffy, they get a special boomkin one! */
		 
		 /* First get the protocol */
		 global $protocol;
		
		if( $this->isFluffy() ) {
			
			switch($this->race) {
				
				case 6:
					return "/media/images/icons/fluffykin.png";
					break;
				
				case 8:
					return "/media/images/icons/fluffykin.png";
					break;
				
			}
			
		} else {
			
			return $protocol ."://eu.battle.net/static-render/eu/". $this->thumbnail;
			
		}
		
	}
	
	public function getAverageItemLevel($equipped = false) {
		
		/* This function gets the data regarding a users average item level and returns it */
		if( $bnet_data = $this->getBattleNetData("items") ) {
			
			switch($equipped) {
				
				case true:
					return $bnet_data->items->averageItemLevelEquipped;
					break;
					
				case false:
					return $bnet_data->items->averageItemLevel;
					break;
				
			}
			
		}
		
	}
	
	public function isClaimed() {
		
		/* This function checks if this character has a valid Account ID */
		if(isset($this->account_id)) {
			
			return true;
			
		}
		
		return false;
		
	}
	
	public function verify($slot1, $slot2) {
		
		$bnet_data = $this->getBattleNetData("items");
		
		if( empty($bnet_data->items->$slot1) && empty($bnet_data->items->$slot2) ) {
			
			/* If it's dropped into here it means that both slots are empty */
			return true;
			
		} else {
			
			/* Else return false */
			return false;
			
		}
		
	}
	
	private function getBattleNetData($fields) {
	
		/* This function accepts a comma seperated list of fields to get from battle.net
		 * Goes and fetches it, then decodes the data and returns it for use */
		
		/* Get the data from battle.net */
		$json = file_get_contents('http://eu.battle.net/api/wow/character/Tarren-Mill/'. $this->name .'?fields='. $fields);
		
		if ( $bnet_data = json_decode($json) ) {
		
			/* Return the data */
			return $bnet_data;	
			
		} else {
		
			/* Return false */
			return false;	
			
		}
		
	}
	
	public function isOfficer() {
		
		if($this->rank <= 2 || $this->rank == 5) {
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	public function isModerator() {
		
		if($account = $this->getAccount()) {
			
			if($account->isModerator()) {
				
				return true;
				
			}
			
		}
		
		return false;
		
	}
	
	public function isFluffy() {
		
		/* Decode this data */
		$bnet_data = $this->getBattleNetData("talents");
		
		if( ($this->class == 11) && ($bnet_data->talents[0]->name == "Balance" || $bnet_data->talents[1]->name == "Balance") ) {
			return true;
		} else {
			return false;
		}
		
	}
	
}

// 7. News class
class news_item {
	
	/* This class provides us with the means of displaying the news items and appropriate comments */
	
	// Variables
	public $id;
	private $author_id;
	private $date;
	public $title;
	public $content;
	private $comments_allowed;
	
	public function __construct($news_item_id) {
		
		// First, set up a new instance of the database
		$db = db();
		
		// Query the database to see if we can find the news item by that ID
		if( $result = $db->query("SELECT * FROM `news_items` WHERE `id` = $news_item_id LIMIT 0, 1") ) {
			
			// Fetch an object based on the result
			$news_item = $result->fetch_object();
			
			// Free the result set
			$result->close();
			
			// Close the database connection
			$db->close();
			
			// Now we can start setting the variables
			$this->id = $news_item->id;
			$this->author_id = $news_item->author_account_id;
			$this->date_published = $news_item->date_published;
			$this->title = $news_item->title;
			$this->content = $news_item->content;
			$this->comments_allowed = $news_item->comments_allowed;
		
			// Return as true
			return true;
			
		} else {
		
			// Call the destruction function to close down the instance of this class
			$this->__destruct();
			
			// Close the database connection
			$db->close();
			
			// Return as false
			return false;
			
		}
		
	}
	
	public static function getArticles($limit = 10, $start = 0) {
		
		// First, set up a new instance of the database
		$db = db();
		
		// Query the database to see if we can find the news items based on the values provided
		if ( $result = $db->query("SELECT `id` FROM `news_items` ORDER BY `date_published` LIMIT $start, $limit") ) {
			
			// Fetch an array based on the result
			$array = $result->fetch_array();
			
			// Free the result set
			$result->close();
			
			// Close the database connection
			$db->close();
			
			// Return the array as a result of the function
			return $array;
			
		} else {
			
			// If we've dropped into here it means that something went wrong
			// So close the database connection
			$db->close();
			
			// And return out false
			return false;
			
		}
		
	}
	
	public function getAuthor() {
		
		// This function gets the author's primary character and returns the character ID 
		// which we can use to create a character object

		// Create a new account object based on the account ID of this news instance
		$account = new account($this->author_id);
		
		// Return the ID of the primary character
		return $account->primary_character;
		
	}
	
	public function getDate() {
		
		// Calculate the formatted date
		$formatted_date = date('jS F Y', $this->date);
		
		// Return the formatted date
		return $formatted_date;
		
	}
	
	public function getTime() {
		
		// Calcualte the formatted time
		$formatted_time = date('H:i T', $this->date);
		
		return $formatted_time;
		
	}
	
	public function commentsAllowed() {
		
		switch($this->comments_allowed) {
			
			case 0:
				return false;
				break;
				
			case 1:
				return true;
				break;
			
		}
		
	}
	
	public function countComments() {
		
		// This function counts the number of comments recorded for this item
		
		// First, create a new instance of the database
		$db = db();
		
		// Query the database to get the number of comments
		if( $result = $db->query("SELECT `id` FROM `news_comments` WHERE `news_item_id` = ". $this->id) ) {
			
			// If we've dropped into here then that means we're on the right track
			$rows = $result->num_rows;
			
			// Free the result set
			$result->close();
			
			// Close the database connection
			$db->close();
			
			// And return the number of rows
			return $rows;
			
		} else {
			
			// Oh dear, there are no comments.
			// Close the database connection
			$db->close();
			
			// And return out 0
			return 0;
			
		}
		
	}
	
	public function getComments() {
		
		// Get all the comments related to this article
		// However, in this circumstance we only want to get the comments that are parents, 
		// not children of other comments as these are called elsewhere in the application.
		
		// Create a new instance of the database
		$db = db();
		
		// Query the database to get the ID numbers of all the comments for this article
		if( $result = $db->query("SELECT `id` FROM `news_comments` WHERE `news_item_id` = ". $this->id ." AND `comment_in_reply_to_id` IS NULL ORDER BY `date_published`") ) {
			
			// If we've dropped in here it means we were able to do this
			
			// Create an array from the result set
			$array = $result->fetch_array();
			
			// Free the result set
			$result->close();
			
			// Close the database connection
			$db->close();
			
			// Return the array as the output from this function
			return $array;
			
		} else {
			
			// Oh dear, something went wrong
			
			// Close the database connection
			$db->close();
			
			// And return out false
			return false;
			
		}
		
	} 
}

// 8. News comment class
class news_comment {
	
	/* This class provides us with the means of generating all the comments for a specific news article */
	
	// Variables
	private $id;
	private $news_item_id;
	private $author_id;
	private $in_reply_to_id;
	private $date;
	public $content;
	
	public function __construct($comment_id) {
		
		// This function constructs the news_comment object
		
		// First, instanciate a new object of the database
		$db = db();
		
		// Next, run the query to select this particular comment from the database
		if ( $result = $db->query("SELECT * FROM `news_comments` WHERE `id` = $comment_id LIMIT 0, 1") ) {
			
			// If we dropped into here, fantastic, it means we were able to find the comment in question
			// Let's create an object from the result
			$obj = $result->fetch_object();
			
			// Now we can free the result set
			$result->close();
			
			// And close the database connection as well
			$db->close();
			
			// Let's start setting the objects variables
			$this->id = $obj->id;
			$this->news_item_id = $obj->news_item_id;
			$this->author_id = $obj->author_account_id;
			$this->in_reply_to_id = $obj->comment_in_reply_to_id;
			$this->date = $obj->date_published;
			$this->content = $obj->content;
			
			// And finally return true
			return true;
			
		} else {
			
			// Oh dear, we weren't able to get that specific comment. Something must have gone wrong.
			// Close the database connection
			$db->close();
			
			// And return out false
			return false;
			
		}
		
	}
	
	public function getAuthor() {
		
		// This function gets the author's primary character and returns the character ID
		// which we can use to create a character object
		
		// Create a new account object based on the account ID of this object's instance
		$account = new account($this->author_id);
		
		// Return the ID number of the primary character
		return $account->primary_character;
		
	}
	
	public function getDate() {
		
		// Calculate the formatted date
		$formatted_date = date('jS F Y', $this->date);
		
		return $formatted_date;
		
	}
	
	public function getTime() {
		
		// Calculate the formatted time
		$formatted_time = date('H:i T', $this->date);
		
		// Return the formatted time
		return $formatted_time;
		
	}
	
	public function getChildComments() {
		
		// This function gets any child comments and returns their ID numbers in an array
		
		// First instanciate a new database object
		$db = db();
		
		// Query the database to find the comments we want
		if( $result = $db->query("SELECT `id` FROM `news_comments` WHERE `comment_in_reply_to_id` = ". $this->id ." ORDER BY `date_published`") ) {
			
			// If we dropped into here, fantastic, it means we found some child comments.
			// Fetch an array from the result set
			$array = $result->fetch_array();
			
			// Now free up the result set
			$result->close();
			
			// And close the database connection
			$db->close();
			
			// Finally, return the array we just generated
			return $array;
			
		} else {
			
			// If we dropped into here, not to worry, it just means there are no more child comments
			// So we first close the database connection
			$db->close();
			
			// And return out false
			return false;
			
		}
		
	}
}

// 9. Applications
class applying_character {

	/* Variables */
	private $id;
	private $player;
	public $name;
	private $realm;
	private $class;
	private $race;
	private $gender;
	private $level;
	private $achievementPoints;
}

?>