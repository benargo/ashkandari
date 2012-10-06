<?php

/**
 * Team class
 * This class handles the teams for Ashkandari
 */

class Team {

	// Variables
	private $id;
	private $slug;
	private $progression;
	private $members = array();
	private $leader;

	/**
	 * Construction function
	 * @param $id (int) Team ID number
	 */
	public function __construct($id) {

		// Load the actual team
		$query = new DBQuery("
			SELECT
				`id`,
				`slug`,
				`size`,
				`leader`
			FROM `teams`
			WHERE `id` = $id
			LIMIT 0, 1");

		if($query->result()) {

			foreach($query->columns() as $key => $value) {

				$this->{$key} = $value;
			}
		}

		// Load team members
		$query = new DBQuery("
			SELECT `character_id`
			FROM `team_members`
			WHERE `team_id` = ".$this->id);

		if($query->result()) {

			foreach($query->rows() as $row) {

				$this->members[] = $row;
			}
		}
	}

	/**
	 * Load team by slug
	 * @param $slug (varchar 32) => Team slug
	 */
	public static function load($slug) {

		$query = new DBQuery("
			SELECT `id`
			FROM `teams`
			WHERE `slug` = '$slug'
			LIMIT 0, 1");

		if($id = $query->value()) {

			return new Team($id);
		}

		return false;
	}

	/**
	 * Get team URL
	 */
	public function getURL() {

		return BASE_URL.'/team/'.$this->slug;
	}

	/**
	 * Get progression
	 */
	public function getProgression() {

		return new TeamProgression($this->id);
	}

	/**
	 * Get team members
	 */
	public function getMembers() {


	}

}