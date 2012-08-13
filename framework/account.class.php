<?php

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
	public $forum_signature;
	private $forum_moderator;
	public $primary_character;
	private $news;
	private $digest;
	
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
		$this->news = $account->news;
		$this->digest = $account->digest;
		
		// Close the database connection
		$db->close();
		
		// And return true
		return true;
		
	}
	
	public function changeEmail($value) {
		
		$db = db();
		
		if($value != NULL) {
			
			$this->email = $value;
			$db->query("UPDATE `accounts` SET `email` = '$value', `active` = 0 WHERE `id` = ". $this->id);
			$db->close();
			return true;
			
		} else {
			
			$db->close();
			return false;
			
		}
		
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
	
	/* Get forum signature */
	public function setSignature($content = NULL) {
		
		/* This function sets the forum signature for the account, and updates the database */
		$db = db();
		
		$this->forum_signature = $content;
		if($db->query("UPDATE `accounts` SET `forum_signature` = '$content' WHERE `id` = ". $this->id)) {
			
			$db->close();
			return true;
			
		}
	
		$db->close();
		return false;
		
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
	 
	public function subscribedNews() {
		 
		switch($this->news) {
		
			case 0:
				return false;
				break;
			
			case 1:
				return true;
				break;
			
			default:
				return true;
				break;
		}
		 
	}
	
	public function subscribedDigest() {
		 
		switch($this->digest) {
		
			case 0:
				return false;
				break;
			
			case 1:
				return true;
				break;
			
			default:
				return true;
				break;
		}
		 
	}
	
	public function setEmailPreferences($news = 1, $digest = 1) {
		
		$db = db();
		
		$this->news = $news;
		$this->digest = $digest;
		
		if($db->query("UPDATE `accounts` SET `news` = $news, `digest` = $digest WHERE `id` = ". $this->id)) {
			
			$db->close();
			return true;
			
		}
		
		$db->close();
		return false;
		
	}
	
}

?>
