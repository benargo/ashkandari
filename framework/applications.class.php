<?php

/* This page controls the class for applying characters
 * It contains a set of primary functions that will get
 * all the applications of a certain type.
 * The remainder is a fully functioning object oriented
 * class that represents an individual application. */
 
function getApplications($type = NULL) {
	
	/* This function gets all the applications from the database
	 * By switching through the type that's requested from the function */
	 
	/* Declare a database instance */
	$db = db();
	
	/* Switch through the available types */
	switch($type) {
		
		/* Outstanding applications */
		case "undecided":
			$result = $db->query("SELECT `id` FROM `applications` WHERE `decision` IS NULL ORDER BY `received_date` DESC");
			break;
			
		case "accepted":
			$result = $db->query("SELECT `id` FROM `applications` WHERE `decision` = 1 ORDER BY `received_date` DESC");
			break;
			
		case "declined":
			$result = $db->query("SELECT `id` FROM `applications` WHERE `decision` = 0 ORDER BY `received_date` DESC");
			break;
			
		default:
			$result = $db->query("SELECT `id` FROM `applications` ORDER BY `received_date` DESC");
			break;	
		
	}
	
	/* Close the database connection */
	
	$db->close();
	return $result;
	
}

class application {

	/* Variables */
	public $id;
	public $name;
	private $realm;
	protected $bnet_json;
	public $english;
	public $teamspeak;
	public $microphone;
	public $played_since;
	public $q1;
	public $q2;
	public $q3;
	public $q4;
	private $active_spec;
	private $off_spec;
	public $received_date;
	private $decision;
	public $decision_date;
	private $officer;
	private $forum_thread;
	
	/* Construction Function */
	public function __construct($id) {
		
		/* Define a database connection */
		$db = db();
		
		/* Get the application from the database */
		$result = $db->query("SELECT * FROM `applications` WHERE `id` = $id LIMIT 0, 1");
		
		/* Fetch an object from the result set */
		$application = $result->fetch_object();
		
		/* Now free up the result set */
		$result->close();
		
		/* Start setting the basic variables for this instance */
		$this->id = $application->id;
		$this->name = $application->character;
		$this->realm = $application->realm;
		
		/* Now get their realm */
		$realm = $this->getRealm();
		
		/* Get the battle.net data for this character */
		$this->bnet_json = file_get_contents("http://eu.battle.net/api/wow/character/". $realm->slug ."/". $this->name ."?fields=items,talents,progression,professions");
		
		/* Continue setting basic variables for this instance */
		$this->english = $application->english;
		$this->teamspeak = $application->teamspeak;
		$this->microphone = $application->microphone;
		$this->played_since = $application->played_since;
		$this->q1 = $application->q1;
		$this->q2 = $application->q2;
		$this->q3 = $application->q3;
		$this->q4 = $application->q4;
		$this->active_spec = $application->active_spec;
		
		/* Work out their off spec */
		if($application->active_spec == 0) {
			
			$this->off_spec = 1;
			
		} else {
			
			$this->off_spec = 0;
			
		}
		
		/* Continue setting basic variables for this instance */
		$this->received_date = $application->received_date;
		$this->decision = $application->decision;
		$this->decision_data = $application->decision_date;
		$this->officer = $application->officer_account_id;
		$this->forum_thread = $application->forum_thread_id;
		
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
	}
	
	/* Decode their battle.net data */
	public function getBattleNetData() {
		
		/* Using the JSON Decode function, get their battle.net data from this instance */
		$bnet_decoded = json_decode($this->bnet_json);
		
		/* And return it */
		return $bnet_decoded;
		
	}
	
	/* Get their realm */
	public function getRealm() {
		
		/* Get the realm using the global getRealm function */
		$realm = getRealm($this->realm);
		
		/* Return this realm */
		return $realm;
		
	}
	
	/* Get their class */
	public function getClass() {
		
		/* Get their battle.net data */
		$bnet_data = $this->getBattleNetData();
		
		/* Get their class ID from the data */
		$class_id = $bnet_data->class;
		
		/* Using the global getClass function, get their class object based on the given class ID */
		$class = getClass($class_id);
		
		/* And return this new class object */
		return $class;
		
	}
	
	/* Get their race */
	public function getRace() {
		
		/* Get their battle.net data */
		$bnet_data = $this->getBattleNetData();
		
		/* Get their race ID from the data */
		$race_id = $bnet_data->race;
		
		/* Using the global getRace function, get their race object based on the given race ID */
		$race = getRace($race_id);
		
		/* And return the new race object */
		return $race;
		
	}
	
	/* Get their racial icon */
	public function getRaceIcon() {
		
		/* Get their battle.net data */
		$bnet_data = $this->getBattleNetData();
		
		/* Get their race ID from the data */
		$race_id = $bnet_data->race;
		
		/* Get their race using the global getRace function */
		$race = getRace($race_id);
		
		/* Get their gender from the battle.net data */
		$gender = $bnet_data->gender;
		
		/* Switch through the genders to get the correct icon */
		switch($gender) {
	
			/* Male */
			case 0:
				return $race->male_icon;
				break;
				
			/* Female */
			case 1:
				return $race->female_icon;
				break;
	
		}
		
	}
	
	/* Get Gender */
	public function getGender() {
		
		/* Get their battle.net data */
		$bnet_data = $this->getBattleNetData();
		
		/* Get their gender */
		$gender = $bnet_data->gender;
		
		return $gender;
		
	}
	
	/* Get their level */
	public function getLevel() {
		
		/* Get their battle.net data */
		$bnet_data = $this->getBattleNetData();
		
		/* Get their level from the battle.net data */
		$level = $bnet_data->level;
		
		/* And return it */
		return $level;
		
	}
	
	/* Get their total number of achievement points */
	public function getAchievementPoints() {
		
		/* Get their battle.net data */
		$bnet_data = $this->getBattleNetData();
		
		/* Get their number of achievement points from the data */
		$points = $bnet_data->achievementPoints;
		
		/* And return it */
		return $points;
		
	}
	
	/* Get their avatar */
	public function getAvatar() {
		
		/* Get their battle.net data */
		$bnet_data = $this->getBattleNetData();
		
		/* Get the global protocol */
		global $protocol;
		
		/* Get their thumbnail and append it onto a base string */
		$thumbnail = $protocol ."://eu.battle.net/static-render/eu/". $bnet_data->thumbnail;
		
		/* And return it */
		return $thumbnail;
		
	}
	
	/* Get their average item level */
	public function getItemLevel() {
		
		/* Get their battle.net data */
		$bnet_data = $this->getBattleNetData();
		
		/* Get their average item level */
		$item_level = $bnet_data->items->averageItemLevel;
		
		/* And return it */
		return $item_level;
		
	}
	
	/* Get their average equipped item level */
	public function getEquippedItemLevel() {
		
		/* Get their battle.net data */
		$bnet_data = $this->getBattleNetData();
		
		/* Get their average equipped item level */
		$item_level = $bnet_data->items->averageItemLevelEquipped;
		
		/* And return it */
		return $item_level;
		
	}

	/* Get primary profession */
	public function getProfession($position = 0) {
			
		/* Get their profession based on the $postion */
		$profession = new profession($this->id, $position);
		
		/* And return this as an standard class object */
		return $profession;
		
	}
	
	/* Get first aid skill */
	public function getFirstAid() {
		
		/* Get an instance of the first aid class */
		$first_aid = new first_aid($this->id);
		
		/* And return it */
		return $first_aid;
		
	}
	
	/* Get fishing skill */
	public function getFishing() {
		
		/* Get an instance of the fishing class */
		$fishing = new fishing($this->id);
		
		/* And return it */
		return $fishing;
		
	} 
	
	/* Get cooking skill */
	public function getCooking() {
		
		/* Get an instance of the cooking class */
		$cooking = new cooking($this->id);
		
		/* And return it */
		return $cooking;
		
	}
	
	/* Get their active spec */
	public function getPrimarySpec() {
		
		/* Create an instance of the spec based on their active spec */
		$spec = new spec($this->id, $this->active_spec);
		
		/* And return it */
		return $spec;
		
	}
	
	/* Get their off spec */
	public function getOffSpec() {
		
		/* Create an instance of the spec object based on their off spec */
		$spec = new spec($this->id, $this->off_spec);
		
		/* And return it */
		return $spec;
		
	}
	
	/* Get their progression based on a raid ID */
	public function getProgression($raid_name) {
		
		/* Create a new progression instance */
		$progression = new progression($this->id, $raid_name);
		
		return $progression;
		
	}
	
	/* Get the decision */
	public function getDecision() {
		
		/* Switch through the existing status */
		
	}
	
	/* Get account of officer who made decision */
	public function getOfficer() {
		
		/* Create a new account based on the officer */
		$officer_account = new account($this->officer);
		
		/* Get their primary character */
		$officer_character = $officer_account->getPrimaryCharacter();
		
		/* And return their primary character */
		return $officer_character;
		
	}
	
	/* Get the thread ID */
	public function getThread() {
		
		/* Create a new forum thread based on the ID of this instance */
		$thread = new forum_thread($this->forum_thread);
		
		/* And return it */
		return $thread;
		
	}
	
	/* Set the Thread ID */
	public function setThread($thread_id) {
		
		/* Create a database connection */
		$db = db();
		
		/* Update the application in the database */
		$db->query("UPDATE `applications` SET `forum_thread_id` = $thread_id WHERE `id` = ". $this->id);
		
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
		
	}
	
	
}

class profession extends application {
	
	/* Variables */
	public $name;
	private $icon;
	public $skill;
	
	/* Construction function */
	public function __construct($application_id, $position = 0) {
		
		/* Construct an instance of the application */
		$application = new application($application_id);
		
		/* Get the battle.net data */
		$bnet_data = $application->getBattleNetData();
		
		/* Get their profession based on the position */
		$profession = $bnet_data->professions->primary[$position];
		
		/* Set the variables */
		$this->name = $profession->name;
		$this->icon = $profession->icon;
		$this->skill = $profession->rank;
		
		/* And return true */
		return true;
		
	}
	
	/* Get the icon from battle.net */
	public function getIcon() {
		
		$url = "http://eu.media.blizzard.com/wow/icons/56/". $this->icon .".jpg";
		
		return $url;
		
	}
	
	/* Get the percentage complete */
	public function getPercentage() {
		
		/* Calculate the percentage */
		$percentage = ($this->skill / 600)*100;
		
		/* Return the percentage */
		return $percentage;
		
	}
	
}

class first_aid extends application {
	
	/* Variables */
	public $skill;
	private $icon;
	
	/* Construction function */
	public function __construct($application_id) {
		
		/* Construct an instance of the application */
		$application = new application($application_id);
		
		/* Get the battle.net data */
		$bnet_data = $application->getBattleNetData();
		
		/* Set the fishing skill */
		$this->skill = $bnet_data->professions->secondary[0]->rank;
		
		/* Set the icon data */
		$this->icon = $bnet_data->professions->secondary[0]->icon;
		
	}
	
	/* Get the icon from battle.net */
	public function getIcon() {
		
		/* Construct the URL */
		$url = "http://eu.media.blizzard.com/wow/icons/56/". $this->icon .".jpg";
		
		/* And return it */
		return $url;
		
	}
	
	/* Get the percentage complete */
	public function getPercentage() {
		
		/* Calculate the percentage */
		$percentage = ($this->skill / 600)*100;
		
		/* Return the percentage */
		return $percentage;
		
	}
	
}

class fishing extends application {
	
	/* Variables */
	public $skill;
	private $icon;
	
	/* Construction function */
	public function __construct($application_id) {
		
		/* Construct an instance of the application */
		$application = new application($application_id);
		
		/* Get the battle.net data */
		$bnet_data = $application->getBattleNetData();
		
		/* Set the fishing skill */
		$this->skill = $bnet_data->professions->secondary[2]->rank;
		
		/* Set the icon data */
		$this->icon = $bnet_data->professions->secondary[2]->icon;
		
	}
	
	/* Get the icon from battle.net */
	public function getIcon() {
		
		/* Construct the URL */
		$url = "http://eu.media.blizzard.com/wow/icons/56/". $this->icon .".jpg";
		
		/* And return it */
		return $url;
		
	}
	
	/* Get the percentage complete */
	public function getPercentage() {
		
		/* Calculate the percentage */
		$percentage = ($this->skill / 600)*100;
		
		/* Return the percentage */
		return $percentage;
		
	}
	
}

class cooking extends application {
	
	/* Variables */
	public $skill;
	private $icon;
	private $grill;
	private $great_grill;
	private $oven;
	private $great_oven;
	private $pot;
	private $great_pot;
	private $steamer;
	private $great_steamer;
	private $wok;
	private $great_wok;
	private $pandaren;
	private $great_pandaren;
	
	/* Construction function */
	public function __construct($application_id) {
		
		/* Construct an instance of the application */
		$application = new application($application_id);
		
		/* Get the battle.net data */
		$bnet_data = $application->getBattleNetData();
		
		/* Set the cooking skill */
		$this->skill = $bnet_data->professions->secondary[3]->rank;
		
		/* Set the icon data */
		$this->icon = $bnet_data->professions->secondary[3]->icon;
	
		/* Get the array of recipes */
		$recipes = $bnet_data->professions->secondary[3]->recipes;
	
		/* Now check if they can make each of the banquets */
		/* Banquet of the Grill */
		if(in_array(125141, $recipes)) {
			
			$this->grill = 125141;
			
		}
		
		/* Great Banquet of the Grill */
		if(in_array(125142, $recipes)) {
			
			$this->great_grill = 125142;
			
		}
		
		/* Banquet of the Oven */
		if(in_array(125600, $recipes)) {
			
			$this->oven = 125600;
			
		}
		
		/* Great Banquet of the Oven */
		if(in_array(125601, $recipes)) {
			
			$this->great_oven = 125601;
			
		}
		
		/* Banquet of the Pot */
		if(in_array(125596, $recipes)) {
			
			$this->pot = 125596;
			
		}
		
		/* Great Banquet of the Pot */
		if(in_array(125597, $recipes)) {
			
			$this->great_pot = 125597;
			
		}
		
		/* Banquet of the Steamer */
		if(in_array(125598, $recipes)) {
			
			$this->steamer = 125598;
			
		}
		
		/* Great Banquet of the Steamer */
		if(in_array(125599, $recipes)) {
			
			$this->great_steamer = 125599;
			
		}
		
		/* Banquet of the Wok */
		if(in_array(125594, $recipes)) {
			
			$this->wok = 125594;
			
		}
		
		/* Great Banquet of the Wok */
		if(in_array(125595, $recipes)) {
			
			$this->great_wok = 125595;
			
		}
		
		/* Pandaren Banquet */
		if(in_array(105190, $recipes)) {
			
			$this->pandaren = 105190;
			
		}
		
		/* Great Pandaren Banquet */
		if(in_array(105194, $recipes)) {
			
			$this->great_pandaren = 105194;
			
		}
		
	}
	
	/* Get the icon from battle.net */
	public function getIcon() {
		
		/* Construct the URL */
		$url = "http://eu.media.blizzard.com/wow/icons/56/". $this->icon .".jpg";
		
		/* And return it */
		return $url;
		
	}
	
	/* Get the percentage complete */
	public function getPercentage() {
		
		/* Calculate the percentage */
		$percentage = ($this->skill / 600)*100;
		
		/* Return the percentage */
		return $percentage;
		
	}
	
	/* Check if they have a banquet */
	public function hasBanquet($banquet_name) {
		
		/* If they do have this banquet */
		if(isset($this->$banquet_name)) {
			
			/* Return the banquet recipe ID */
			return $this->$banquet_name;
			
		}
		
		/* No feast, return false */
		return false;
		
	}
	
}

class spec extends application {
	
	/* Variables */
	public $name;
	private $icon;
	
	/* Construction function */
	public function __construct($application_id, $spec_id) {
		
		/* Create an instance of the application */
		$application = new application($application_id);
		
		/* Get the battle.net data */
		$bnet_data = $application->getBattleNetData();
		
		/* Get the spec we're after */
		$spec = $bnet_data->talents[$spec_id];
		
		/* Start setting variables */
		$this->name = $spec->name;
		$this->icon = $spec->icon;
		
	}
	
	/* Get Icon */
	public function getIcon() {
		
		/* Construct the URL */
		$url = "http://eu.media.blizzard.com/wow/icons/56/". $this->icon .".jpg";
		
		/* And return it */
		return $url;
		
	}
	
}

class progression extends application {
	
	/* Variables */
	private $position;
	private $bnet_data;
	private $raid_id;
	public $raid_name;
	public $normal;
	public $heroic;

	/* Construction function */
	public function __construct($application_id, $raid_name) {

		/* Create an instance of the application */
		$application = new application($application_id);
		
		/* Get the battle.net data */
		$this->bnet_data = $application->getBattleNetData();
		
		/* Get the raid position */
		/* Switch through all the possible alternatives */
		switch($raid_name) {
			
			case "Firelands":
				$raid_pos = 25;
				break;
			
			case "Dragon Soul":
				$raid_pos = 26;
				break;
			
		}
		
		/* Set the raid position */
		$this->position = $raid_pos;
		
		/* Get the raid itself */
		$raid = $bnet_data->progression->raids[$race_pos];
		
		/* Start setting variables */		
		$this->raid_id = $raid->id;
		$this->raid_name = $raid->name;
		$this->normal = $raid->normal;
		$this->heroic = $raid->heroic;
		
	}
	
	/* Count the number of bosses */
	public function countBosses() {
		
		/* Get the battle.net data */
		
		/* Count the number of bosses */
		$bosses = count($this->bnet_data->progression->raids[$this->position]->bosses);
		
		/* And return it */
		return $bosses;
		
	}
	
	/* Get a specific boss */
	public function getBoss($position) {

		
		/* Get the boss */
		$boss = $this->bnet_data->progression->raids[$this->position]->bosses[$position];
		
		/* And return it */
		return $boss;
		
	}
	
}


