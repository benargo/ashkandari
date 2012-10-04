<?php

/**
 * Account class
 * This class controls the primary authentication methods for Ashkandari's guild website.
 * It's functions include:
 * - Accessing user information
 * - Authenticating users
 * - Managing access levels and permissions
 * - Linking up to characters
 * - Tracing content
 */

class Account {

	// Variables
	public $id;
	private $email;
	private $password;
	private $application;
	private $activation_code;
	private $active;
	private $suspended;
	private $forum_signature;
	private $forum_moderator;
	private $administrator;
	private $news;
	private $digest;
	private $primary_character;
	private $characters = array();

	/**
	 * Construction function
	 * This function will set most of the variables above based on a user lookup.
	 * @param $id (int) => account ID number
	 */
	public function __construct($id) {

		// Query the database
		$query = new DBQuery("
		SELECT
			`id`,
			`email`,
			`password`,
			`activation_code`,
			`active`,
			`suspended`,
			`application`,
			`forum_signature`,
			`forum_moderator`,
			`administrator`,
			`primary_character`,
			`news`,
			`digest`
		FROM
			`accounts`
		WHERE
			`id` = $id
		LIMIT
			0, 1");

		// Loop through each of the columns and
		// Start setting variables
		foreach($query->columns() as $key => $value) {

			$this->{$key} = $value;
		}

		// Get the characters
		// Query the database
		$query = new DBQuery("
			SELECT
				`id`
			FROM
				`characters`
			WHERE
				`account_id` = ".$this->id);

		// Loop through the characters
		foreach($query->rows() as $row) {

			$this->characters[] = $row;
		}
	}

	/**
	 * Load account
	 * @param $id (int) => account ID number
	 * @return instance of Account
	 */
	public static function load($id) {

		// Query the database
		$query = new DBQuery("
			SELECT
				`id`
			FROM
				`accounts`
			WHERE
				`id` = $id
			LIMIT 0, 1");

		// Check if we got an account
		if($query->result()) {
			return new Account($query->value());
		}

		return false;
	}

	/**
	 * Load account
	 * @param $email (varchar) => email address
	 * @return instance of Account
	 */
	public static function loadByEmail($email) {

		// Query the database
		$query = new DBQuery("
			SELECT
				`id`
			FROM
				`accounts`
			WHERE
				`email` = 'email'
			LIMIT 0, 1");

		// Check if we got an account
		if($query->result()) {
			return new Account($query->value());
		}

		return false;
	}

	/**
	 * Process an email address change request
	 * @param $email (varchar) => new email address
	 */
	public function changeEmail($email) {

		// Prepare the activation email
		$email = new Email(
			$email,
			'Please confirm your email address',
			'<p>Dear '.$this->getPrimaryCharacter()->name.',</p>
			<p>Someone (hopefully you) changed the email address on your Ashkandari.com account just now.
			In order to finalise the change, we need you to confirm your new email address by clicking on the following link:</p>
			<p><a href="'.BASE_URL.'/account/email/confirm/'.$this->id.'/'.encrypt($email).'">'.
				BASE_URL.'/account/email/confirm/'.$this->id.'/'.encrypt($email).'</a></p>
			<p>If you can\'t click on the link, copy and paste it into your web browser.</p>');

		// Send the email
		$email->send();
	}

	/**
	 * Change the user's email address
	 * @param $email (varchar) => new email address
	 */
	public function setEmail($email) {

		$this->email = $email;

		// Update the database
		DBQuery::runQuery("
			UPDATE
				`accounts`
			SET
				`password` = '$email'
			WHERE
				`id` = ".$this->id);

	}

	/**
	 * Change user's password
	 * @param $password (varchar) => new password
	 */
	public function setPassword($password) {

		$query = new DBQuery("
			UPDATE
				`accounts`
			SET
				`password` = '".md5($password)."'
			WHERE
				`id` = ".$this->id);

	}

	/**
	 * Authenticate user
	 * @param $password (varchar) => user password
	 */
	public function authenticate($password) {

		// Check if the password is correct
		if($this->password == md5($password)) {

			return true;
		}

		return false;
	}

	/**
	 * Activate account
	 * @param $code (varchar) => activation code
	 */
	public function activate($code) {

		// Check the activation code
		if($this->activation_code == $code) {

			// Update the database
			DBQuery::runQuery("
				UPDATE
					`accounts`
				SET
					`activation_code` = '".md5(time())."',
					`active` = 1
				WHERE
					`id` = ".$this->id);

			return true;
		}

		return false;
	}

	/**
	 * Check if the user's password is empty
	 */
	public function isPasswordEmpty() {

		if(empty($this->password)) {

			return true;
		}

		return false;
	}

	/**
	 * Get this users forum signature
	 */
	public function getSignature() {

		return htmlentities($this->forum_signature);
	}

	/**
	 * Set this users forum signature
	 * @param $signature (varchar)
	 */
	public function setSignature($signature) {

		$this->forum_signature = $signature;

		DBQuery::runQuery("
			UPDATE
				`accounts`
			SET
				`forum_signature` = '".DBQuery::escape($signature)."'
			WHERE
				`id` = ".$this->id);
	}

	/**
	 * Check if this user is a forum moderator
	 */
	public function isModerator() {

		return (bool) $this->moderator;
	}

	/**
	 * Check if this user is a website administrator
	 */
	public function isAdmin() {

		return (bool) $this->administrator;
	}

	/**
	 * Check if this user is a guild officer
	 */
	public function isOfficer() {

		return $this->getPrimaryCharacter()->isOfficer();
	}

	/**
	 * Check if this user has an application linked to it
	 */
	public function hasApplication() {

		if(isset($this->application)) {

			return true;
		}

		return false;
	}

	/**
	 * Get this character's application
	 */
	public function getApplication() {

		if($this->hasApplication()) {

			return new Application($this->application);
		}

		return false;
	}

	/**
	 * Check if this user has a primary character
	 */
	public function hasPrimaryCharacter() {

		if(isset($this->primary_character)) {

			return true;
		}

		return false;
	}

	/**
	 * Set this user's primary character
	 * @param $id (int) => character ID number
	 */
	public function setPrimaryCharacter($id) {

		// Check if this character ID is owned by this account
		if(in_array($id, $this->characters)) {

			$this->primary_character = $id;

			// Update the database
			DBQuery::runQuery("
				UPDATE 
					`accounts`
				SET 
					`primary_character` = $id
				WHERE 
					`id` = ".$this->id);

			return true;
		}

		return false;
	}

	/**
	 * Get this user's primary character
	 */
	public function getPrimaryCharacter() {

		if($this->hasPrimaryCharacter()) {

			return new Character($this->primary_character);
		}

		return false;
	}

	/**
	 * Get all of this user's characters
	 */
	public function getAllCharacters() {

		return $this->characters;
	}

	/**
	 * Is the user subscribed to the news channel?
	 */
	public function isSubscribedNews() {

		return (bool) $this->news;
	}

	/**
	 * Is the user subscribed to the digest?
	 */
	public function isSubscribedDigest() {

		return (bool) $this->digest;
	}

	/**
	 * Update the user's subscriptions
	 */
	public function setSubscriptions($news = 1, $digest = 1) {

		$this->news = $news;
		$this->digest = $digest;

		DBQuery::runQuery("
			UPDATE 
				`accounts`
			SET 
				`news` = $news,
				`digest` = $digest
			WHERE 
				`id` = ".$this->id);
	}

}