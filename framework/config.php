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
 * 4. Configuration class
 * 5. Account class
 ***************************************************/

// 1. Pre-flight checks

// Start by turning sessions on
session_start();

error_reporting(E_ALL);

// Determine the protocol (HTTP or HTTPS)
if( empty($_SERVER["HTTPS"]) ) {
	$protocol = "http";
} else {
	$protocol = "https";
}

// 2. Constants
define("PATH", "/home/ashkandari/");
define("BASE_URL", $protocol."://ashkandari.com");

// 3. Database integration
// This is in a seperate file for security reasons :P
require_once(PATH."db/mysqli_connection.php");

// 4. Configuration class
class config {
	
	/* This class needs to handle a number of things, namely:
	 * Setting up the database
	 * Getting all the races, classes & realms
	 * Getting specific races, classes & realms
	 * Getting all of the available item slots out of the database for further use
	 */
	
    /* Construction function
	 * This class is never likely to be instantiated, as it consists purely of static variables
	 * As a result, the constructor simply calls the destructor as a security measure 
	 * NB: We do not need a __destruct function as the basic PHP one will do fine */
	public function __construct() {
		$this->__destruct();
	}
	
    /* Create a new instance of our database
	 * NB: This function returns an OBJECT */
	public static function db() {

		// First, get the variables from the included file
		global $db_details;

		// Create the new MySQLi object
		$db = new mysqli($db_details["host"], $db_details["user"], $db_details["password"], $db_details["name"]);

		// And return it for our use.
		return $db;
		
	}
	
    /* Get all the realms from the database
	 * NB: This function returns an ARRAY */
	public static function getAllRealms() {
		
		// Set up a new instance of the database
		$db = self::db();
		
		// Run the query to select all realms
		if( $result = $db->query("SELECT * FROM `conf_realms` ORDER BY `name`") ) {
								
			// Close database connection
			$db->close();
			
			// And return the result set
			return $result;
		
		}
		
	}
	
    /* Get a specific realm, identified by its index (`id`)
	 * NB: This function returns an OBJECT */
	public static function getRealm($realm_id = 201) {
		
		// Set up a new instance of the database
		$db = self::db();
		
		// Run the query to select the realm
		if( $result = $db->query("SELECT * FROM `conf_realms` WHERE `id` = $realm_id LIMIT 0, 1") ) {
			
			// Fetch an array of the row
			$realm = $result->fetch_object();
			
			// Free result set
			$result->close();
			
		}
		
		// Close database connection
		$db->close();
		
		// And return the array
		return $realm;

	}
	
    /* Get all the classes from the database
	 * NB: This function returns an RESULT SET */
	public static function getAllClasses() {
		
		// Set up a new instance of the database
		$db = self::db();
		
		// Run the query to select the classes
		if( $result = $db->query("SELECT * FROM `conf_classes` ORDER BY `name`") ) {
			
			// Close database connection
			$db->close();
		
			// And return the object
			return $result;
			
		} else {
			
			// Close database connection
			$db->close();
			
			// And return false
			return false;
		}
		
	} // getAllClasses()
	
    /* Get a specific class, identified by its index (`id`)
	 * NB: This function returns an OBJECT */
	public static function getClass($class_id) {
		
		// Set up a new instance of the database
		$db = self::db();
		
		// Run the query to select the class
		if( $result = $db->query("SELECT * FROM `conf_classes` WHERE `id` = $class_id LIMIT 0, 1") ) {
			
			// Fetch an array of the row
			$class = $result->fetch_object();
			
			// Free result set
			$result->close();
			
		}
		
		// Close database connection
		$db->close();
		
		// And return the object
		return $class;
		
	} // getClass()
	
    /* Get all the races from the database
	 * NB: This function returns an OBJECT */
	public static function getAllRaces() {
		
		// Set up a new instance of the database
		$db = self::db();
		
		// Run the query to select the races
		if( $result = $db->query("SELECT * FROM `conf_races` ORDER BY `faction`, `name`") ) {
			
			// Close database connection
			$db->close();
		
			// And return the object
			return $result;
			
		} else {
			
			// Close database connection
			$db->close();
			
			// And return false
			return false;
		}
		

		
	} // getAllRaces()
	
    /* Get all the races of a specific faction from the database
     * NB: This function returns a result set */
	public static function getRacesByFaction($faction = "horde") {
		
		// Set up a new instance of the database
		$db = self::db();
		
		// Run the query to select the races
		if( $result = $db->query("SELECT * FROM `conf_races` WHERE `faction` = '$faction' ORDER BY `name`") ) {
			
			// Close database connection
			$db->close();
		
			// And return the object
			return $result;
			
		} else {
			
			// Close database connection
			$db->close();
			
			// And return false
			return false;
		}
		
	} // getRacesByFaction
	
	/* Get a specific race from the database by its index (`id`)
	 * NB: This function returns an OBJECT */
	public static function getRace($race_id) {
		
		// Set up a new instance of the database
		$db = self::db();
		
		// Run the query to select the race
		if( $result = $db->query("SELECT * FROM `conf_races` WHERE `id` = $race_id LIMIT 0, 1") ) {
			
			// Fetch an object of the above query
			$race = $result->fetch_object();
			
			// Free result set
			$result->close();
			
		}
		
		// Close database connection
		$db->close();
		
		// And return the object
		return $race;
		
	}
	
	public static function getAllRanks() {
		
		/* This function gets all the available ranks from the database
		 * NB: This function returns a RESULT SET */
		
		/* Set up a new instance of the database */ 
		$db = self::db();
		
		/* Query the database */
		if( $result = $db->query("SELECT * FROM `guild_ranks` ORDER BY `id`") ) {
			
			/* Close the database connection */
			$db->close();
			
			/* Return the result set */
			return $result;
			
		} else {
			
			/* Close the database connection */
			$db->close();
			
			/* Return out false */
			return false;
			
		}
		
	}
	
	public static function getRandomItemSlot($not = "offHand") {
	
		/* This function checks the database for two random item slots, based on the criteria required
		 * and returns the result set */
		
		// First, include the database
		$db = self::db();
		
		if( $result = $db->query("SELECT * FROM `conf_item_slots` WHERE `used_for_verification` = 1 AND `id` <> '$not' ORDER BY RAND() LIMIT 0, 1") ) {
		
			// The fact that it's dropped into here is a good sign, it means that we got 2 items back
			
			// Create an object from the result set
			$obj = $result->fetch_object();
			
			// Free the result set
			$result->close();
			
			// Close the database connection
			$db->close();
			
			// Return the object
			return $obj;
		
		} else {
		
			$db->close();
			die('<p class="error">Sorry, but there was a problem with the validation service. Please <a href="/apply/">go back</a> and try again.</p>');
		
		}
	
	} 
	
} // class config

// 5. Account class
class account {
	
	/* This class is the basis for the entire operation
	 * It is the parent class for many other classes, and performs a large number of functions
	 * Accessing user information
	 * Authenticating Users
	 * Managing access levels and permissions
	 * Linking up to characters
	 * And tracing content
	 */
	
	/* Variables
	 * This class has a wide number of variables attached to it
	 * and are laid out below */
	protected $id;
	protected $email;
	private $password;
	protected $security_question;
	protected $security_answer;
	private $activiation_code;
	private $active;
	private $suspended;
	protected $forum_signature;
	protected $forum_moderator;
	protected $officer;
	protected $administrator;
	protected $primary_character;
	
	/* Construction function
	 * This function will set most of the variables above based on a user lookup 
	 * It is most likely to be called during an authentication */
	public function __construct($account_id) {
		
		// First, set up a new instance of the database
		$db = config::db();

		// Query the database to see if we can find this particular user
		if( $result = $db->query("SELECT * FROM `acc_accounts` WHERE `id` = $account_id LIMIT 0, 1") ) {
			
			// Fetch an object based on the result
			$account = $result->fetch_object();
			
			// Free result set
			$result->close();
			
		} else { // It obviously didn't go so well :<
			
			// Call the destruction function to close down this instance of the class
			$this->__destruct();
			
			// Close the database connection
			$db->close();
			
			// Return as false
			return false;
			
		}
		
		// Now we can start setting the variables
		$this->id = $account->id;
		$this->email = $account->email;
		$this->password = $account->password;
		$this->security_question = $account->security_question;
		$this->security_answer = $account->security_answer;
		$this->activation_code = $account->activation_code;
		$this->active = $account->active;
		$this->forum_signature = $account->forum_signature;
		$this->forum_moderator = $account->forum_moderator;
		$this->officer = $account->officer;
		$this->administrator = $account->administrator;
		$this->primary_character = $account->primary_character;
		
		// Close the database connection
		$db->close();
		
		// And return true
		return true;
		
	}
	
	/* Lookup account by email address function
	 * This PRIVATE function allows us to look up an account ID for further use within the class purely by supplying an email address.
	 * It does not return anything else about the account */
	protected function getAccountIdFromEmail($email) {
		
		// First, set up a new instance of the database
		$db = config::db();
		
		// Query the database to see if we can find the account
		if( $result = $db->query("SELECT `id` FROM `acc_accounts` WHERE `email` = '$email' LIMIT 0, 1") ) {
			
			// If we're in here, then it means we've found a proper account and we can return the value
			
			// Create an object of the result
			$obj = $result->fetch_object();
			
			// Free result set
			$result->close();
			
		} else {
			
			// Oh dear, seems that we cannot find the account with the credentials given to us.
			
			// Close the database connection here
			$db->close();
			
			// For security reasons we cannot tell them whether its a problem with their username or password
			// so we just have to throw it back to them saying that it was the wrong error.
			return false;
			
		}
		
		// Close the database connection
		$db->close();
		
		// Return the account ID
		return $obj->id;
		
	} // getAccountIdFromEmail()
	
	/* Authentication function
	 * This STATIC function will authenticate users against the database and return one of three strings
	 * which can be switched through to determine the following action */
	public static function authenticate($email, $raw_password) {
		
		// First, set up a new instance of the database
		$db = config::db();
		
		// Next, lookup the account ID from the email address given
		$id = self::getAccountIdFromEmail($email);
		
		// Set up the password by encrypting it
		$password = md5($raw_password);
		
		// Query the database to find a user with the specified email address and password
		// Remember the email address is a unique index, but not the primary key.
		if( $result = $db->query("SELECT `id`, `active`, `suspended` FROM `acc_accounts` WHERE `id` = $id AND `password` = '$password' LIMIT 0, 1") ) {
			
			// If it's dropped into here it means that we can find an account with the correct credentials
			// However, we now need to check if it's been properly activated and it's not suspended
			
			// First though, set the result to an instance of an OBJECT
			$account = $result->fetch_object();
			
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
    			
			} else {
    			
    			// Well, this must mean the account is inactive
    			return 'inactive';
    			
			}
			
		} else {

			// Oh dear, seems that we cannot find the account with the credentials given to us.

			// Close the database connection here
			$db->close();

			// For security reasons we cannot tell them whether its a problem with their username or password
			// so we just have to throw it back to them saying that it was the wrong error.
			return 'fail';

		}
		
	} // authenticate();

	/* Activate account function
	 * This STATIC function takes in an activation code and account ID as provided and successfully activates the account.
	 * If the password field is also NULL, as a result of a password reset or this is the first time someone is activating
	 * their account after applying to the guild, then it will also prompt them to enter a new password. */
	public static function activate($id, $code) {
		
		// First, set up a new instance of the database
		$db = config::db();
		
		// Query the database to find a match between the two supplied items
		if( $result = $db->query("SELECT `id`, `password`, `activation_code`, `active` FROM `acc_accounts` WHERE `id` = $id AND `activation_code` = '$code' LIMIT 0, 1") ) {
			
			// If we've dropped into here it means that we've successfully found an account and the activation code matches.
			
			// Create an object from the result set
			$obj = $result->fetch_object();
			
			// Free the result set
			$result->close();
			
			// Now we need to check if they have a NULL password
			if( is_null($obj->password) ) {
			
    			// Close the database connection
    			$db->close();
				
				// If it's dropped into this section then it means we need to ask them to set a new password
				return 'new_password_required';
				
			}
			
			// Create a new activation code to use next time
			$new_code = md5(time());
			
			// Set the account to active and update the activation code
			$db->query("UPDATE `acc_accounts` SET `activation_code` = '$new_code', `active` = 1 WHERE `id` = $id LIMIT 0, 1");
			
			// Close the database connection
			$db->close();
			
			// Return out of this function
			return 'success';
			
		} else {
		
    		// Oh dear, we weren't able to find an account with that ID or activation code so we have to exit out false.
    		
    		// Close the database connection
			$db->close();
			
			// Return out false
			return false;
    		
		}
		
	}
	
	 /* Officer check function
	  * This function will check against the current account object to see if they are an officer or not
	  * This helps in user permissions and enables us to display certain content to officers of the guild */
	 public function isOfficer() {
    	
    	// Do a check to see if this is user is an officer or not.
    	if ($this->officer) {
        	
        	// If we've dropped into here then it means that the user is an officer.
        	return true;
        	
    	}
    	
    	return false;
    	
	}
	
	 /* Moderator check function
	  * This function will check against the current account object to see if they are authentciated as a moderator or not
	  * Note that for the purposes of this function, and the seniority of officer status over moderator status, it will match
	  * both officer status or moderator status and return true. */
	 public function isModerator() {
		 
		 if($this->moderator || $this->officer) {
			 
			 // If we've dropped into here then it means that the user is either an officer or a moderator
			 return true;
			 
		 }
		 
		 return false;
		 
	 }
	 
	
} // class account

// 6. Character class
class character {
	
	/* This class controls all the characters registered in the database, and the various items about them. */
	
	// Variables
	private $id;
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
		$db = config::db();
		
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
		$db = config::db();
		
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
	
	public function getAccount() {
		
		// Quite simply, we need to return an instance of the account object based on this character
		$account = new account($this->account_id);
		
		// And return it out for us to use
		return $account;
		
	}
	
	public function getClass() {
		
		// This function gets the class from configuration class
		$class = config::getClass($this->class);
		return $class;
		
	}
	
	public function getRace() {
		
		// This function gets the race from the config class and returns it for us to use
		$race = config::getRace($this->race);
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
		
		$db = config::db();
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
		$db = config::db();
		
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
	
	public function isFluffy() {
		
		/* Get the talents data from battle.net */
		$json = file_get_contents('http://eu.battle.net/api/wow/character/Tarren-Mill/'. $this->name .'?fields=talents');
		
		/* Decode this data */
		$bnet_data = json_decode($json);
		
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
		$db = config::db();
		
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
		$db = config::db();
		
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
		$db = config::db();
		
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
		$db = config::db();
		
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
		$db = config::db();
		
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
		$db = config::db();
		
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