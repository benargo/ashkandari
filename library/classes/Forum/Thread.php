<?php

/**
 * Forum thread class
 */

class ForumThread {

	// Variables
	private $id;
	private $board;
	private $author;
	private $title;
	private $sticky;
	private $locked;
	private $posts = array();

	/**
	 * Construction function
	 * @param $id (int) => Thread ID
	 */
	public function __construct($id) {

		$query = new DBQuery("
			SELECT
				`id`,
				`board`,
				`author`,
				`title`,
				`sticky`,
				`locked`
			FROM `forum_threads`
			WHERE `id` = $id
			LIMIT 0, 1");

		if($query->result()) {

			foreach($query->columns() as $key => $value) {

				$this->{$key} = $value;
			}
		}
	}

	/**
	 * Get this thread's board
	 */
	public function getBoard() {

		if(is_int($this->board)) {

			$this->board = new Forum($this->board);
		}

		return $this->board;
	}

	/**
	 * Get this thread's original author
	 */
	public function getAuthor() {

		if(is_int($this->author)) {

			$this->author = new Account($this->author);
		}

		return $this->author;
	}
}