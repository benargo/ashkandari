<?php

/**
 * Forum board class
 */

class Forum {

	// Variables
	private $id;
	public $name;
	private $description;
	private $officer_only;
	private $locked;
	private $threads = array();
	private $number_of_posts = 0;
	private $latest_post_time = 'Never';

	/**
	 * Construction function
	 * @param $id (int) => board ID
	 */
	public function __construct($id) {

		$query = new DBQuery("
			SELECT
				`id`,
				`name`,
				`description`,
				`officer_only`,
				`locked`
			FROM `forum_boards`
			WHERE `id` = $id
			LIMIT 0, 1");

		if($query->result()) {

			foreach($query->columns() as $key => $value) {

				(($key == 'officer_only' || $key == 'locked')
					? $this->{$key} = (bool) $value
					: $this->{$key} = $value);
			}
		}
	}

	/**
	 * Count the number of forum boards
	 */
	public static function countBoards() {

		return count(self::getBoards());
	}

	/**
	 * Get all forum boards
	 */
	public static function getBoards(Account $account = NULL) {

		$boards = array();

		$query = new DBQuery("
			SELECT `id`
			FROM `forum_boards`
			ORDER BY `order`");

		if($query->result()) {

			foreach($query->rows() as $row) {

				$boards[$row['id']] = new Forum($row['id']);
			}

			return $boards;
		}

		return false;
	}

	/**
	 * Get the URL of this board
	 */
	public function getURL() {

		return BASE_URL.'/forums/'.toSlug($this->name);
	}

	/**
	 * Get the description of this board
	 */
	public function getDescription() {

		return htmlentities($this->description);
	}

	/**
	 * Check if the user is allowed to view this board
	 * @param $account (instance of Account)
	 */
	public function isAuthorised(Account $account) {

		if(($this->officer_only && $account->isOfficer()) || !$this->officer_only) {

			return true;
		}

		return false;
	}

	/**
	 * Check if this forum is locked
	 */
	public function isLocked() {

		return $this->locked;
	}

	/**
	 * Can this account create new threads?
	 * @param $account (instance of Account)
	 */
	public function canCreateThreads(Account $account) {

		if(($this->isLocked() && $account->isOfficer()) || !$this->isLocked()) {

			return true;
		}
	}

	/**
	 * Get this board's threads
	 */
	public function getThreads() {

		if(empty($this->threads)) {

			$query = new DBQuery("
				SELECT `id`
				FROM `forum_threads`
				WHERE `board` = ".$this->id);

			if($query->result()) {

				foreach($query->rows() as $row) {

					$this->threads[$row['id']] = new ForumThread($row['id']));
				}
			}
		}

		return $this->threads;
	}

	/**
	 * Count the number of threads
	 */
	public function countThreads() {

		if(empty($this->threads)) {

			$this->getThreads();
		}

		return count($this->threads);
	}

	/**
	 * Count the number of posts
	 */
	public function countPosts() {

		if(empty($this->number_of_posts)) {

			if(empty($this->threads)) {

				$this->getThreads();
			}

			foreach($this->threads as $id => $thread) {

				$this->number_of_posts = $this->number_of_posts + $thread->countPosts();
			}
		}

		return $this->number_of_posts;
	}

	/**
	 * Get the time and author of the latest post
	 * @param $dateformat (string) => Date format
	 */
	public function getLatestPost($dateformat = NULL) {

		if($this->latest_post_time === 'Never') {

			if(empty($this->threads)) {

				$this->getThreads();
			}

			// Build an array with the post IDs only
			foreach($this->threads as $id => $thread) {

				$_time = $thread->getLatestPost()->getTime();

				if($_time > $this->latest_post_time) {

					$this->latest_post_time = $_time;
				}
			}
		}

		// If a date format is provided, we can format it as such
		if(isset($dateformat)) {

			return date($dateformat, $this->latest_post_time);
		}

		return $this->latest_post_time;
	}

}