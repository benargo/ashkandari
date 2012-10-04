<?php

/**
 * Character class
 * This class controls all the characters registered in the database, and the various items about them.
 */

class Character {

	// Variables
	public $id;
	private $account;
	public $name;
	private $rank;
	private $bnet;

	/**
	 * Construction function
	 * @param $id (int) => Character ID number
	 */
	public function __construct($id) {

		// Query the database to get the character
		$query = new DBQuery("
			SELECT
				`id`,
				`account`,
				`name`,
				'rank'
			FROM
				`characters`
			WHERE
				`id` = $id
			LIMIT 0, 1");

		// Loop through each of the columns
		foreach($query->columns() as $key => $value) {
			$this->{$key} = $value;
		}
	}

	/**
	 * Get a character by name
	 * @param $name (varchar) => Character name
	 */
	public static function getCharacterByName($name) {

		// Query the database
		$query = new DBQuery("
			SELECT
				`id`
			FROM
				`characters`
			WHERE
				`name` = '$name'
			LIMIT 0, 1");

		if($query->result()) {

			return new Character($query->value());
		}

		return false;
	}

	/**
	 * Get all characters
	 */
	public static function getAllCharacters() {

		$characters = array();

		// Query the database
		$query = new DBQuery("
			SELECT
				`id`
			FROM
				`characters`");

		foreach($query->rows() as $row) {

			$characters[$row->id] = new Character($row->id);
		}

		return $characters;
	}

	/**
	 * Decode their battle.net data
	 */
	private function getBattleNetData() {

		$query = new DBQuery;

		// Query the database
		$query->query("
			SELECT
				`bnet`
			FROM
				`characters`
			WHERE
				`id` = ".$this->id);

		if($query->result()) {

			$data = json_decode($query->value());

			// If the last modified is older than 24 hours ago
			// then we want to refresh the data
			if($data->lastModified < time()-24*60*60) {

				$this->bnet = file_get_contents('http://eu.battle.net/api/wow/character/tarren-mill/'.$this->character.'?fields='
					.'feed,'
					.'items,'
					.'professions,'
					.'stats,'
					.'talents,'
					.'titles');

				// Update the database
				DBQuery::runQuery("
					UPDATE
						`characters`
					SET
						`bnet` = '".$this->bnet."'
					WHERE
						`id` = ".$this->id);
			} else {

				$this->bnet = $query->value();
			}

			return json_decode($this->bnet);
		}

		$this->bnet = file_get_contents('http://eu.battle.net/api/wow/character/tarren-mill/'.$this->character.'?fields='
					.'feed,'
					.'items,'
					.'professions,'
					.'stats,'
					.'talents,'
					.'titles');

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
	 * Check if this character has an account
	 */
	public function hasAccount() {

		if(isset($this->account)) {

			return true;
		}

		return false;
	}

	/**
	 * Get this character's account
	 */
	public function getAccount() {

		if($this->hasAccount()) {

			return new Account($this->account);
		}

		return false;
	}

	/**
	 * Check if we own this character
	 * @param $_SESSION['account'] (encrypted int) => Account ID
	 */
	public function isThisMine() {

		if($this->hasAccount() && $this->account == decrypt($_SESSION['account'])) {

			return true;
		}

		return false;
	}

	/**
	 * Get this character's class
	 */
	public function getClass() {

		return new Class($this->getBattleNetData()->class);
	}

	/**
	 * Get this character's race
	 */
	public function getRace() {

		return new Race($this->getBattleNetData()->race);
	}

	/**
	 * Get this character's gender
	 */
	public function getGender() {

		switch($this->getBattleNetData()->gender) {

			case 0:
			case 'male':
				return 'male';
				break;

			case 1:
			case 'female':
				return 'female';
				break;
		}
	}

	/**
	 * Get this character's level
	 */
	public function getLevel() {

		return $this->getBattleNetData()->level;
	}

	/**
	 * Get this character's rank
	 */
	public function getRank() {

		return new Rank($this->rank)
	}

	/**
	 * Get this character's number of achievement points
	 */
	public function getAchievementPoints() {

		return $this->getBattleNetData()->achievementPoints();
	}

	/**
	 * Get this character's currently selected title
	 */
	public function getTitle() {

		foreach($this->getBattleNetData()->titles as $title) {

			if($title->selected) {

				return str_replace('%s', $this->name, $title->name)
			}
		}
	}

	/**
	 * Get this character's active spec
	 */
	public function getActiveSpec() {

		foreach($this->getBattleNetData()->talents as $spec) {

			if($spec->selected) {

				return $spec->spec->name;
			}
		}
	}

	/**
	 * Get this character's off spec
	 */
	public function getOffSpec() {

		foreach($this->getBattleNetData()->talents as $spec) {

			if(!$spec->selected) {

				return $spec->spec->name;
			}
		}
	}

	/**
	 * Get this character's thumbnail
	 */
	public function getThumbnail() {

		if($this->isFluffy()) {

			return BASE_URL.'/media/images/icons/fluffykin.jpg';
		}

		return PROTOCOL.'://eu.battle.net/static-render/eu/'.$this->getBattleNetData()->thumbnail;
	}

	/**
	 * Get this character's item level
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
	 * Get this characters professions
	 */
	public function getProfessions() {

		$professions = array();

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
	 * Get this character's EPGP
	 */
	public function getEPGP() {

		$epgp = new EPGP;

		return $epgp->getCharacter($this->id);
	}

	/**
	 * Verify whether someone owns this character and then claim it for them.
	 * @param $slots (array) => item slot ids
	 */
	public function verify($slot1, $slot2) {

		if(empty($this->getBattleNetData()->items->{$slot1}) && empty($this->getBattleNetData()->items->{$slot2}) {

			return true;
		}

		return false;
	}

	/**
	 * Check if this character is an officer
	 * NOTE: This does not equate to a website administrator, which is limited to Animorphus, Orcysama and Naradak
	 */
	public function isOfficer() {

		return $this->getRank()->isOfficer();
	}

	/**
	 * Get this players other characters
	 */
	public function getAlts() {

		return $this->getAccount()->getAllCharacters();
	}
}
