<?php

/**
 * Race class
 * This class handles the various World of Warcraft races
 */

class Race {

	// Variables
	private $xml = simplexml_load_file(BASE_PATH.'/data/xml/races.xml');
	public $id;
	private $faction;
	public $name;
	private $slug;
	private $male_icon;
	private $female_icon;

	/**
	 * Construction function
	 * @param $id (int) => Race ID
	 */
	public function __construct($id) {

		foreach($this->xml->row as $row) {

			if($row->id == $id) {

				foreach($row as $key => $value) {

					$this->{$key} = $value;
				}
			}
		}
	}

	/**
	 * Get the Race's faction
	 */
	public function getFaction() {

		return ucfirst($this->faction);
	}

	/**
	 * Get the Roster URL
	 */
	public function getRosterURL() {

		return BASE_URL.'/roster/class/'.$this->slug;
	}

	/**
	 * 	Get the racial icon
	 * @param $gender (int/varchar) => Gender identifier
	 */
	public function getIcon($gender) {

		switch($gender) {

			// Male
			case 0:
			case 'male':

				return BASE_URL.$this->male_icon;

			break;

			// Female
			case 1:
			case 'female':

				return BASE_URL.$this->female_icon;

			break;
		}

		// Incorrect gender?
		return BASE_URL.'/media/images/icons/race_unknown.jpg';
	}
}