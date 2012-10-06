<?php

/**
 * DBQuery class
 * This class is the whole basis for all the database interaction
 */

class DBQuery {

	// Variables
	protected $connection;
	protected $result;

	/**
	 * Construction function
	 * @param $query (SQL) => SQL query
	 */
	public function __construct($query = NULL) {

		// Create the database connection
		$this->connection = new mysqli(
			DB_HOST,
			DB_USERNAME,
			DB_PASSWORD,
			DB_DATABASE);

		if(isset($query)) {

			$this->query($query);
		}
	}

	/**
	 * Internal query function
	 * @param $query (SQL) => SQL Query
	 */
	public function query($query) {

		if($this->result = $this->connection->query($query)) {

			return true;
		}

		return true;
	}

	/**
	 * Static query function
	 * @param $query (SQL) => SQL Query
	 */
	public static function runQuery($query) {

		$connection = new mysqli(
			DB_HOST,
			DB_USERNAME,
			DB_PASSWORD,
			DB_DATABASE);

		if($result = $connection->query($query)) {

			return $result;
		}

		return false;
	}

	/**
	 * Check if we got a correct result
	 */
	public function result() {

		if(isset($this->result->num_rows >= 1)) {

			return true;
		}

		return false;
	}

	/**
	 * Loop through each of the rows and return them
	 */
	public function rows() {

		if($this->result()) {

			$rows = array();

			while($row = $this->result->fetch_assoc()) {

				array_push($rows, $row);
			}

			return $rows;
		}

		return false;
	}

	/**
	 * Loop through each of the columns of this current row
	 */
	public function columns() {

		if($this->result()) {

			$columns = array();

			foreach($this->result->fetch_assoc() as $key => $value) {

				$columns[$key] = $value;
			}

			return $columns;
		}

		return false;
	}

	/**
	 * Get a single value back
	 * Useful if the query returns a single row and a single column
	 */
	public function value() {

		if($this->result()) {

			$row = $this->result->fetch_array();
			return $row[0];
		}

		return false;
	}

}