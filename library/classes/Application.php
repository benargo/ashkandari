<?php

/**
 * Application class
 * This class controls the applications system for Ashkandari.
 */

class Application {

	// Variables
	public $id;
	private $character;
	private $realm;
	private $bnet;
	private $email;
	private $social;
	private $english;
	private $teamspeak;
	private $microphone;
	private $played_since;
	private $q1;
	private $q2;
	private $q3;
	private $q4;
	private $primary_spec;
	private $off_spec;
	private $received_date;
	private $decision;
	private $decision_date;
	private $officer;
	private $forum_thread;

	/**
	 * Construction function
	 * @param $id (int) => Application ID number
	 */
	public function __construct($id) {

		// Query the database to find this application
		$query = new DBQuery("
			SELECT
				`id`,
				`character`,
				`realm`,
				`email`,
				`social`,
				`english`,
				`teamspeak`,
				`microphone`,
				`played_since`.
				`q1`,
				`q2`,
				`q3`,
				`q4`,
				`primary_spec`,
				`received_date`,
				`decision`,
				`decision_date`,
				`officer`,
				`forum_thread`
			FROM
				`applications`
			WHERE
				`id` = $id
			LIMIT 0, 1");

		// Set class variables
		foreach($query->columns() as $key => $value) {
			$this->{$key} = $value;
		}
	}

	/**
	 * Get applications by decision
	 * This function gets the applications from the database and allows us to loop through them.
	 */
	public static function getApplications($decision = 'All') {

		$applications = array();
		$query = new DBQuery;

		// Switch through the decisions
		switch($decision) {

			case 0:
			case 'Undecided':
				$query->query("
					SELECT
						`id`
					FROM
						`applications`
					WHERE
						`decision` = 0");
				break;

			case 1:
			case 'Accepted':
				$query->query("
					SELECT
						`id`
					FROM
						`applications`
					WHERE
						`decision` = 1");
				break;

			case 2:
			case 'Declined':
				$query->query("
					SELECT
						`id`
					FROM
						`applications`
					WHERE
						`decision` = 2");
				break;

			case 'All':
				$query->query("
					SELECT
						`id`
					FROM
						`applications`");
				break;

		}

		// Loop through each of the applications
		foreach($query->rows() as $row) {
			$applications[] = $row->value();
		}

		return $applications;

	}

	/**
	 * Verify their ownership of this character
	 */
	public static function verify($realm_id, $character_name, $slot1, $slot2) {

		$realm = new Realm($realm_id);
		$data = json_decode(file_get_contents('http://eu.battle.net/api/wow/character/'.$realm->slug.'/'.$character_name.'?fields=items'));

		// If these two slots are empty
		if(empty($data->items->{$slot1}) && empty($data->items->{$slot2})) {

			return true;
		}

		return false;
	}

	/**
	 * Load battle.net data
	 * This function is not called during the construction function as it would take too long to load if being called repeatedly
	 */
	private function getBattleNetData() {

		$query = new DBQuery;
		$realm = new Realm($this->realm);

		// Query the databasy
		$query->query("
			SELECT
				`bnet`
			FROM
				`applications`
			WHERE
				`id` = ".$this->id);

		if($query->result()) {

			$data = json_decode($query->value());

			// If the last modified is older than 24 hours ago
			// then we want to refresh the data
			if($data->lastModified < time()-24*60*60) {

				$this->bnet = file_get_contents('http://eu.battle.net/api/wow/character/'.$realm->slug.'/'.$this->character.'?fields=items,talents,progression,professions');

				// Update the database
				DBQuery::runQuery("
					UPDATE
						`applications`
					SET
						`bnet` = '".$this->bnet."'
					WHERE
						`id` = ".$this->id);
			} else {

				$this->bnet = $query->value();
			}

			return json_decode($this->bnet);
		}

		$this->bnet = file_get_contents('http://eu.battle.net/api/wow/character/'.$realm->slug.'/'.$this->character.'?fields=items,talents,progression,professions');

		// Update the database
		DBQuery::runQuery("
			UPDATE
				`applications`
			SET
				`bnet` = '".$this->bnet."'
			WHERE
				`id` = ".$this->id);

		return json_decode($this->bnet);
	}

	/**
	 * Check if they're a social application
	 */
	public function isSocial() {

		return (bool) $this->social;
	}

	/**
	 * Get this character's gender
	 * NOTE: Not the player
	 */
	public function getGender() {

		return $this->getBattleNetData()->gender;
	}

	/**
	 * Get this character's level
	 */
	public function getLevel() {

		return $this->getBattleNetData()->level;
	}

	/**
	 * Get this character's number of achievement points
	 */
	public function getAchievementPoints() {

		return $this->getBattleNetData()->achievementPoints;
	}

	/**
	 * Get this character's average item level
	 */
	public function getItemLevel() {

		return $this->getBattleNetData()->items->averageItemLevel;
	}

	/**
	 * Get this character's equipped item level
	 */
	public function getEquippedItemLevel() {

		return $this->getBattleNetData()->items->averageItemLevelEquipped;
	}

	/**
	 * Get this character's primary professions
	 */
	public function getProfessions() {

		// Create an array
		$professions = array();

		// Primary Professions
		foreach($this->getBattleNetData()->professions->primary as $prof) {

			// Declare the Professions' class name
			$class = str_replace(' ','',$prof->name);

			// Create a new instance of said profession
			$professions[$prof->name] = new $class(json_encode($prof));
		}

		// Secondary Professions
		foreach($this->getBattleNetData()->professions->secondary as $prof) {

			// Declare the Professions' class name
			$class = str_replace(' ','',$prof->name);

			// Create a new instance of said profession
			$professions[$prof->name] = new $class(json_encode($prof));
		}

		return $professions;
	}

	/**
	 * Get this character's primary spec
	 */
	public function getPrimarySpec() {

		return new Spec(json_encode($this->getBattleNetData()->talents[$this->primary_spec]));

	}

	/**
	 * Get this character's off spec
	 */
	public function getOffSpec() {

		// Calculate the off spec
		switch($this->primary_spec) {

			case 0:
				$off_spec = 1;
				break;

			case 1:
				$off_spec = 0;
				break;
		}

		return new Spec(json_encode($this->getBattleNetData()->talents[$off_spec]));
	}

	/**
	 * Get this character's progression
	 */
	public function getProgression() {

		return new Progression(json_encode($this->getBattleNetData()->progression));
	}

	/**
	 * Has this application been decided?
	 */
	private function isDecided() {

		return (bool) $this->decision;
	}

	/**
	 * Get this applications decision
	 */
	public function getDecision() {

		if($this->isDecided()) {

			switch($this->decision) {

				case 1:
				case 'Declined':
					return 'Declined';
					break;

				case 2:
				case 'Accepted':
					return 'Accepted';
					break;
			}
		}

		return false;
	}

	/**
	 * Decide on this application
	 * @param $decision (int) => application decision
	 */
	public function setDecision($decision) {

		if(!$this->isDecided()) {

			switch($decision) {

				case 1:
				case 'Decline':
					$this->decline();
					break;

				case 2:
				case 'Accept':
					$this->accept();
					break;

			}

			// Lock the forum thread
			$this->getThread()->lock();
		}
	}

	/**
	 * Accept this application
	 */
	private function accept() {

		// Get the needed classes
		$officer = new Account($_SESSION['account']);
		$realm = new Realm($this->realm);
		$race = new Race($this->getBattleNetData()->race);

		// Generate an activation code
		$code = md5(time());

		// Set the decision
		$this->decision = 2;

		// Update the database
		DBQuery::runQuery("
			UPDATE
				`applications`
			SET
				`decision` = 2,
				`decision_date` = FROM_UNIXTIME(".time().")
			WHERE
				`id` = ".$this->id);

		// Create an account for the newly accepted member
		$query = new DBQuery("
			INSERT INTO `accounts` (
				`email`,
				`activation_code`,
				`application`)
			VALUES (
				'".$this->email."',
				'".$code."',
				".$this->id.")");

		// Create a new email
		$email = new Email(
			$this->email,
			'Your application was accepted!');

		// Generate the email message
		$email_message = '<p>Dear '.$this->character.',</p>
			<p>Congratulations! Your application to join our guild was accepted.</p>';

		// They need to both change realms and faction change
		if(!$realm->isOurs() && $race->isAlliance()) {

			$email_message .= '<p>Please remember that you need to transfer to Tarren Mill and faction change to Horde before we can invite you to the guild.
			Once you have done that, please contact an officer in-game to be invited to the guild.
			A list of officers can be found online at <a href="http://www.ashkandari.com/roster/officers/">http://www.ashkandari.com/roster/officers/</a>.</p>';
		}

		// They need to change realms
		elseif(!$realm->isOurs()) {

			$email_message .= '<p>Please remember that you need to transfer to Tarren Mill before we can invite you to the guild.
			Once you have done that, please contact an officer in-game to be invited to the guild.
			A list of officers can be found online at <a href="http://www.ashkandari.com/roster/officers/">http://www.ashkandari.com/roster/officers/</a>.</p>';
		}

		// They need to change factions
		elseif($race->isAlliance()) {

			$email_message .= '<p>Please remember that you need to faction change to Horde before we can invite you to the guild.
			Once you have done that, please contact an officer in-game to be invited to the guild.
			A list of officers can be found online at <a href="http://www.ashkandari.com/roster/officers/">http://www.ashkandari.com/roster/officers/</a>.</p>';
		}

		// They don't need to change realms or faction change
		else {

			$email_message .= '<p>Please contact an officer in-game to be invited to the guild.
			A list of officers can be found online at <a href="http://www.ashkandari.com/officers/">http://www.ashkandari.com/officers/</a>.</p>';
		}

		// Continue generating the email message
		$email_message .= '<p>We have generated an account for you on our website, so you can get to work straight away with accessing our guild forums.
		To activate your new account and choose a password, please copy and paste the link below into your web browser:</p>
		<p><a href="https://www.ashkandari.com/account/activate/'. $query->insert_id .'/'. $code .'">https://www.ashkandari.com/account/activate/'. $query->insert_id .'/'. $code .'</a></p>
		<p>Once your account has been activated you will be able to use the full features of our website.</p>
		<p>Once again, congratulations for passing the application process and welcome aboard.</p>';

		// Set the email message
		$email->setMessage($email_message);

		// Set the officer that this email is coming from
		$email->setSender($officer);

		// Send this email
		$email->send();
	}

	/**
	 * Decline this application
	 */
	private function decline() {

		// Get the needed classes
		$officer = new Account($_SESSION['account']);

		// Set the decision
		$this->decision = 1;

		// Update the database
		DBQuery::runQuery("
			UPDATE
				`applications`
			SET
				`decision` = 1,
				`decision_date` = FROM_UNIXTIME(".time().")
			WHERE
				`id` = ".$this->id);

		// Create a new email
		$email = new Email(
			$this->email,
			'Your application was unsuccessful',
			'<p>Dear '.$this->character.',</p>
		<p>Unfortunately your application was unsuccessful and we will not be able to invite you to our guild.</p>
		<p>We wish you all the success with your adventures in the future.');

		// Set the officer that this email is coming from
		$email->setSender($officer->id);

		// Send this email
		$email->send();
	}

	/**
	 * Get the officer who made the final decision
	 */
	public function getOfficer() {

		return new Account($this->officer);
	}

	/**
	 * Set the forum thread
	 * @param $id (int) => Forum thread ID
	 */
	public function setThread($id) {

		$this->forum_thread = $id;

		// Update the database
		DBQuery::runQuery("
			UPDATE
				`applications`
			SET
				`forum_thread` = $id
			WHERE
				`id` = ".$this->id);
	}

	/**
	 * Get the forum thread
	 */
	public function getThread() {

		return new ForumThread($this->forum_thread);
	}

}