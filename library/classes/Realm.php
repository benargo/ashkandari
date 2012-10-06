<?php

/**
 * Realm class
 * This class handles all the realms
 */

class Realm {

	// Variables
	private $xml = file_get_contents(BASE_PATH.'/data/xml/realms.xml');
	public $id;
	public $name;
	public $slug;

	/**
	 * Construction function
	 * @param $id (int) => Realm ID number
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
}