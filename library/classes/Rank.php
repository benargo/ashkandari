<?php

/**
 * Rank class
 * This class handles the guild ranks
 */

class Rank {

	// Variables
	private $id;
	public $name;
	private $slug;
	private $alt;
	private $officer

	/**
	 * Construction function
	 * @param $id (int) => Rank ID number
	 */
	public function __construct($id) {

		$query = new DBQuery("
			SELECT
				`id`,
				`name`,
				`slug`,
				`alt`,
				`officer`
			FROM `ranks`
			WHERE `id` = $id
			LIMIT 0, 1");

		if($query->result()) {

			foreach($query->columns() as $key => $value) {

				$this->{$key} = $value;
			}
		}
	}

	/**
	 * Get Roster URL
	 */
	public function getRosterURL() {

		return BASE_URL.'/roster/rank/'.$this->slug;
	}

	/**
	 * Is this rank an alt status?
	 */
	public function isAlt() {

		return (bool) $this->alt;
	}

	/**
	 * Is this rank an officer status?
	 */
	public function isOfficer() {

		return (bool) $this->officer;
	}

}