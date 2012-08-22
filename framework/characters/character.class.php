<?php

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
	public $ep;
	public $gp;
	private $bnet_json;
	
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
			$this->ep = $obj->ep;
			$this->gp = $obj->gp;
			
			/* Generate the battle.net information */
			$this->bnet_json = file_get_contents("http://eu.battle.net/api/wow/character/tarren-mill/". $this->name ."?fields=items,talents,professions");
		
			// And finally return out true
			return true;
			
		}
		
	}
	
	/* Decode their battle.net data */
	public function getBattleNetData() {
		
		/* Using the JSON Decode function, get their battle.net data from this instance */
		$bnet_decoded = json_decode($this->bnet_json);
		
		/* And return it */
		return $bnet_decoded;
		
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
	
	public function getPrimarySpec() {
		
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

	/* Get primary profession */
	public function getProfession($position = 0) {
			
		/* Get their profession based on the $postion */
		$profession = new char_profession($this->id, $position);
		
		/* And return this as an standard class object */
		return $profession;
		
	}
	
	/* Get first aid skill */
	public function getFirstAid() {
		
		/* Get an instance of the first aid class */
		$first_aid = new char_first_aid($this->id);
		
		/* And return it */
		return $first_aid;
		
	}
	
	/* Get fishing skill */
	public function getFishing() {
		
		/* Get an instance of the fishing class */
		$fishing = new char_fishing($this->id);
		
		/* And return it */
		return $fishing;
		
	} 
	
	/* Get cooking skill */
	public function getCooking() {
		
		/* Get an instance of the cooking class */
		$cooking = new char_cooking($this->id);
		
		/* And return it */
		return $cooking;
		
	}
	
	public function isClaimed() {
		
		/* This function checks if this character has a valid Account ID */
		if(isset($this->account_id)) {
			
			return true;
			
		}
		
		return false;
		
	}
	
	public function setEP($new_ep) {
		
		/* First, set it in this instance */
		$this->ep = $new_ep;
		
		/* Create a database */
		$db = db();
		
		/* Update the database */
		$db->query("UPDATE `characters` SET `ep` = $new_ep WHERE `id` = ". $this->id);
		
		/* Close the database */
		$db->close();
		
		/* Return true */
		return true;
		
	}
	
	public function setGP($new_gp) {
		
		/* First, set it in this instance */
		$this->gp = $new_gp;
		
		/* Create a database */
		$db = db();
		
		/* Update the database */
		$db->query("UPDATE `characters` SET `gp` = $new_gp WHERE `id` = ". $this->id);
		
		/* Close the database */
		$db->close();
		
		/* Return true */
		return true;
		
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

?>