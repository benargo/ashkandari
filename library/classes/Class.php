<?php

/**
 * Character Class
 * This class control's a character's class (I know, confusing huh)?
 */

class Class {

	// Variables
	private $xml = simplexml_load_file(BASE_PATH.'/data/xml/classes.xml');
	public $id;
	public $name;
	public $slug;
	public $colour;
	private $icon_url;

	/**
	 * Construction function
	 * @param $id (int) => class ID number
	 */
	public function __construct($id) {

		foreach($xml->row as $row) {

			if($row->id == $id) {

				foreach($row as $key => $value) {
					$this->{$key} = $value;
				}
			}
		}
	}

	/**
	 * Get roster URL
	 */
	public function getRosterURL() {

		return BASE_URL.'/roster/class/'.$this->slug;
	}

	/**
	 * Get the icon URL
	 */
	public function getIcon() {

		return BASE_URL.$this->icon_url;
	}
}