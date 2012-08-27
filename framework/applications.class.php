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
	private $email;
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
				
		/* Continue setting basic variables for this instance */
		$this->email = $application->email;
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
		$this->decision_date = $application->decision_date;
		$this->officer = $application->officer_account_id;
		$this->forum_thread = $application->forum_thread_id;
		
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
	}
	
	/* Decode their battle.net data */
	public function getBattleNetData() {
	
		if(empty($this->bnet_json)) {
		
			/* Open a database connection */
			$db = db();
		
			/* Get the realm we need */
			$realm = getRealm($this->realm);
			
			/* Get the battle.net data for this character */
			if($bnet_json = file_get_contents("http://eu.battle.net/api/wow/character/". $realm->slug ."/". $this->name ."?fields=items,talents,progression,professions,titles")) {
				
				/* Cache it into the database */
				$db->query("UPDATE `applications` SET `bnet` = '". $bnet_json ."' WHERE `id` = ". $this->id);
				
			} else {
				
				/* Get the cached bnet data from the database */
				$result = $db->query("SELECT `bnet` FROM `applications` WHERE `id` =". $this->id);
				
				/* Fetch the object */
				$obj = $result->fetch_object();
				
				/* Set the bnet JSON */
				$bnet_json = $obj->bnet;
				
			}
			
		}
		
		/* Update the JSON */
		$this->bnet_json = $bnet_json;

		/* Using the JSON Decode function, get their battle.net data from this instance */
		$bnet_decoded = json_decode($this->bnet_json);
		
		/* And return it */
		return $bnet_decoded;
		
	}
	
	/* Verify their ownership */
	public static function verify($realm_id, $name, $slot1, $slot2) {
	
		/* Get the realm */
		$realm = getRealm($realm_id);
		
		$bnet_data = json_decode(file_get_contents("http://eu.battle.net/api/wow/character/". $realm->slug ."/". $name ."?fields=items"));
		
		if( empty($bnet_data->items->$slot1) && empty($bnet_data->items->$slot2) ) {
			
			/* If it's dropped into here it means that both slots are empty */
			return true;
			
		} else {
			
			/* Else return false */
			return false;
			
		}
		
	}
	
	public function getCurrentTitle() {
		
		/* This function fetches a list of titles owned by a character from battle.net
		 * Decodes it, and then displays the characters name alongside their currently
		 * selected title */
		 
		/* Get the data from battle.net */
		$bnet_data = $this->getBattleNetData();
		
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
		
		/* Well maybe we haven't found a title, so let's just return their name */
		return $this->name;
		
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
		$profession = new app_profession($this->id, $position);
		
		/* And return this as an standard class object */
		return $profession;
		
	}
	
	/* Get first aid skill */
	public function getFirstAid() {
		
		/* Get an instance of the first aid class */
		$first_aid = new app_first_aid($this->id);
		
		/* And return it */
		return $first_aid;
		
	}
	
	/* Get fishing skill */
	public function getFishing() {
		
		/* Get an instance of the fishing class */
		$fishing = new app_fishing($this->id);
		
		/* And return it */
		return $fishing;
		
	} 
	
	/* Get cooking skill */
	public function getCooking() {
		
		/* Get an instance of the cooking class */
		$cooking = new app_cooking($this->id);
		
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
	
	/* Check if a decision has been made */
	public function decided() {
		
		if(isset($this->decision)) {
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	/* Get the decision */
	public function getDecision() {
		
		/* Switch through the existing status */
		switch($this->decision) {
			
			case 0:
				return "Declined";
				break;
				
			case 1:
				return "Accepted";
				break;
			
		}
		
	}
	
	/* Make the decision */
	public function decide($decision) {
		
		/* Switch through the decision */
		switch($decision) {
			
			/* Accept them */
			case "Accept":
				$this->accept();
				break;
				
			case "Decline":
				$this->decline();
				break;
			
		}
		
		/* Return true */
		return true;
		
	}
	
	/* Acceptance function */
	private function accept() {
		
		/* Create a database connection */
		$db = db();
		
		/* Create an account instance for the officer */
		$officer = new account($_SESSION['account']);
		
		/* Get the officers primary character */
		$officer_char = $officer->getPrimaryCharacter();
		
		/* Get the officers rank */
		$officer_rank = $officer_char->getRank();
		
		/* Update the database to set accepted to true */
		$db->query("UPDATE `applications` SET `decision` = 1, `decision_date` = ". time() .", `officer_account_id` = ". $officer->id ." WHERE `id` = ". $this->id) or die($db->error);
		
		/* Get the battle.net data */
		$bnet_data = $this->getBattleNetData();
		
		/* Generate the activation code */
		$code = md5(time());
		
		/* Create the account for the player */
		$db->query("INSERT INTO `accounts` (`email`, `activation_code`, `application`) VALUES ('". $this->email ."', '$code', ". $this->id .")") or die($db->error);
		
		/* Get the account ID */
		$account_id = $db->insert_id;
		
		/* Create a new account from the ID we just generated */
		$account = new account($account_id);
		
		/* Create a new character from the account we got */
		$character = $account->getPrimaryCharacter();
		
		/* Get their race */
		$race = $this->getRace();
		
		/* Set the acceptance email subject */
		$subject = "Your Application was Accepted";
		
		/* Generate the email to send to the applicant */
		$message = '<!DOCTYPE html>
		<html>
		<head>
			<title>Your Application was Accepted</title>
			<link type="text/css" rel="stylesheet" href="http://ashkandari.com/css/email.css" />
		</head>
		<body>
			<p>Dear '. $this->name .',</p>
			
			<p>Congratulations! Your application to join our guild was accepted.</p>';	
			
		/* Check if they need to realm change or faction change */
		if($this->realm != 201 && $race->faction == "alliance") {
			
			/* They need to do both */
			$message .= '<p>Please remember that you need to transfer to Tarren Mill and faction change to Horde before we can invite you to the guild. Once you have done that, please contact an officer in-game to be invited to the guild. A list of officers can be found online at <a href="http://ashkandari.com/officers/">http://ashkandari.com/officers/</a>.</p>';
			
		} elseif($this->realm != 201) {
			
			/* They just need to transfer realms */
			$message .= '<p>Please remember that you need to transfer to Tarren Mill before we can invite you to the guild. Once you have done that, please contact an officer in-game to be invited to the guild. A list of officers can be found online at <a href="http://ashkandari.com/officers/">http://ashkandari.com/officers/</a>.</p>';
			
		} elseif($race->faction == "alliance") {
			
			/* They just need to faction change */
			$message .= '<p>Please remember that you need to faction change to Horde before we can invite you to the guild. Once you have done that, please contact an officer in-game to be invited to the guild. A list of officers can be found online at <a href="http://ashkandari.com/officers/">http://ashkandari.com/officers/</a>.</p>';

			
		} else {
			
			/* They don't need to do either */
			$message .= '<p>Please contact an officer in-game to be invited to the guild. A list of officers can be found online at <a href="http://ashkandari.com/officers/">http://ashkandari.com/officers/</a>.</p>';
			
		}
		
		/* Continue the message */
		$message .= '<p>We have generated an account for you on our website, so you can get to work straight away with accessing our guild forums. To activate your new account and choose a password, please copy and paste the link below into your web browser:</p>
		
			<p><a href="https://ashkandari.com/account/activate/'. $account->id .'/'. $code .'">https://ashkandari.com/account/activate/'. $account->id .'/'. $code .'</a></p>
		
			<p>Once your account has been activated you will be able to use the full features of our website.</p>
			
			<p>Once again, congratulations for passing the application process and welcome aboard.</p>
		
			<p>For the Horde!</p>
			<p style="text-style: italics; font-size: 1.5em;">'. $officer_char->name .'</p>
			<p>'. $officer_rank->long_name .' of Ashkandari</p>
			<p><a href="http://ashkandari.com/roster/character/'. $officer_char->name .'">http://ashkandari.com/roster/character/'. $officer_char->name .'</a></p>
		
			<hr />
		
			<footer style="color: #333333;">
				<p><span style="font-weight: bold;">Privacy Notice:</span> The information contained within this email is both private and confidential. If you are not the intended recipient, please delete this email from your system. Ashkandari respects your privacy and will never email you without your concent, nor will we pass on your details to any third party person or organisation under any circumstances. For further information, please visit <a href="http://ashkandari.com/legal/privacy">http://ashkandari.com/legal/privacy</a>. Thank you for your support and cooportation.</p>
				<p><span style="font-weight: bold;">Disclaimer:</span> World of Warcraft&trade;, Mists of Pandaria&trade; and Blizzard Entertainment&trade; are all trademarks or registered trademarks of Blizzard Entertainment Inc. internationally. All related materials, logos, and images are copyright &copy; Blizzard Entertainment Inc. Ashkandari is in no way associated with or endorsed by Blizzard Entertainment.</p>
				<p>Copyright &copy; '. date('Y') .' Ashkandari</p>
			</footer>
		</body>
		</html>';
		
		/* Declare the email headers */
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "From: Ashkandari <applications@ashkandari.com>\r\n";
		
		/* Send the email */
		mail($this->email, $subject, $message, $headers);
		
		/* Close the database connection */
		$db->close();
		
		/* Finally, return true */
		return true;
		
	}
	
	/* Decline function */
	public function decline() {
		
		/* Create a database connection */
		$db = db();
		
		/* Create an account instance for the officer */
		$officer = new account($_SESSION['account']);
		
		/* Get the officers primary character */
		$officer_char = $officer->getPrimaryCharacter();
		
		/* Get the officers rank */
		$officer_rank = $officer_char->getRank();
		
		/* Update the database to set accepted to true */
		$db->query("UPDATE `applications` SET `decision` = 0, `decision_date` = ". time() .", `officer_account_id` = ". $officer->id ." WHERE `id` = ". $this->id) or die($db->error);
		
		/* Prepare the email subject */
		$subject = "Your Application was Unsuccessful";
		
		/* Generate the email to send to the applicant */
		$message = '<!DOCTYPE html>
		<html>
		<head>
			<title>Your Application was Accepted</title>
			<link type="text/css" rel="stylesheet" href="http://ashkandari.com/css/email.css" />
		</head>
		<body>
			<p>Dear '. $this->name .',</p>
			
			<p>Unfortunately your application was unsuccessful and we will not be able to invite you to our guild.</p>
			
			<p>We wish you all the success with your adventures in the future.</p>
		
			<p>For the Horde!</p>
			<p style="text-style: italics; font-size: 1.5em;">'. $officer_char->name .'</p>
			<p>'. $officer_rank->long_name .' of Ashkandari</p>
			<p><a href="http://ashkandari.com/roster/character/'. $officer_char->name .'">http://ashkandari.com/roster/character/'. $officer_char->name .'</a></p>
		
			<hr />
		
			<footer style="color: #333333;">
				<p><span style="font-weight: bold;">Privacy Notice:</span> The information contained within this email is both private and confidential. If you are not the intended recipient, please delete this email from your system. Ashkandari respects your privacy and will never email you without your concent, nor will we pass on your details to any third party person or organisation under any circumstances. For further information, please visit <a href="http://ashkandari.com/legal/privacy">http://ashkandari.com/legal/privacy</a>. Thank you for your support and cooportation.</p>
				<p><span style="font-weight: bold;">Disclaimer:</span> World of Warcraft&trade;, Mists of Pandaria&trade; and Blizzard Entertainment&trade; are all trademarks or registered trademarks of Blizzard Entertainment Inc. internationally. All related materials, logos, and images are copyright &copy; Blizzard Entertainment Inc. Ashkandari is in no way associated with or endorsed by Blizzard Entertainment.</p>
				<p>Copyright &copy; '. date('Y') .' Ashkandari</p>
			</footer>
		</body>
		</html>';
		
		
		/* Declare the email headers */
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "From: Ashkandari <applications@ashkandari.com>\r\n";
		
		/* Mail it */
		mail($this->email, $subject, $message, $headers);
			
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
		
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
?>