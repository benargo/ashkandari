<?php
/* Forum Board class */
class forum_board {
	
	/* Variables */
	public $id;
	public $title;
	public $description;
	private $officers_only;
	private $locked;
	public $order;
	
	/* Construction function */
	public function __construct($id) {
		
		/* Create a database connection */
		$db = db();
		
		/* Select the board from the database */
		$result = $db->query("SELECT * FROM `forum_boards` WHERE `id` = $id LIMIT 0, 1");
		
		/* Create an object out of the result set */
		$board = $result->fetch_object();
		
		/* Free the result set */
		$result->close();
		
		/* Set variables */
		$this->id = $board->id;
		$this->title = $board->name;
		$this->description = $board->description;
		$this->officers_only = $board->officers_only;
		$this->locked = $board->locked;
		$this->order = $board->order;
		
		/* Close the database connection */
		$db->close();
		
		/* And return out true */
		return true;
		
	}
	
	/* Get threads */
	public function getThreads() {
		
		/* Create a database connection */
		$db = db();
		
		/* Query the database to get the list of threads */
		$result = $db->query("SELECT * FROM `forum_threads` WHERE `board_id` = ". $this->id ." ORDER BY `sticky` DESC, `most_recent_post_time` DESC");
		
		/* Close the database connection */
		$db->close();
		
		/* And return the result set */
		return $result;
		
	}
	
	/* Count the number of threads */
	public function countThreads() {
		
		/* Create a database connection */
		$db = db();
		
		/* Query the database to get the threads */
		$result = $db->query("SELECT * FROM `forum_threads` WHERE `board_id` = ". $this->id);
		
		/* Get the number of rows */
		$count = $result->num_rows;
		
		/* Free the result set */
		$result->close();
		
		/* Close the database connection */
		$db->close();
		
		/* And return the number of rows */
		return $count;
		
	}
	
	/* Count the number of posts */
	public function countPosts() {
		
		/* Create a database connection */
		$db = db();
		
		/* Query the database to get the threads */
		$result = $db->query("SELECT * FROM `forum_threads` WHERE `board_id` = ". $this->id);
		
		/* Create a basic number of posts */
		$count = 0;
		
		/* Loop through each of the threads */
		while($t = $result->fetch_object()) {
			
			/* Get the thread object */
			$thread = new forum_thread($t->id);
			
			/* Count the number of posts */
			$num_posts = $thread->countPosts();
			
			/* Set the new number of posts */
			$count = $count + $num_posts;
			
		}
		
		/* And return the file number of posts */
		return $count;
		
	}
	
	/* Get the latest post */
	public function getLatestUpdate() {
		
		/* Create a database connection */
		$db = db();
		
		/* Query the database to get the threads */
		$result = $db->query("SELECT * FROM `forum_threads` WHERE `board_id` = ". $this->id);
		
		/* Create a basic timer */
		$final_time = 0;
		
		/* Loop through each of the threads */
		while($t = $result->fetch_object()) {
		
			/* Get the thread object */
			$thread = new forum_thread($t->id);
			
			/* Get the time of the latest post */
			$time = $thread->update_time;
			
			/* Check it against the final time */
			if($time > $final_time) {
				
				/* This thread's update time is greater than the current record */
				$final_time = $time;
				
			}
		
		}
		
		/* Check that the final time is not 0 */
		if($final_time == 0) {
			
			/* Override it and say the final post was never */
			$final_time = "Never";
			
		} else {
			
			/* Format the final time */
			$final_time = date('j M Y H:i:s', $final_time);
			
		}
		
		/* Return the final time */
		return $final_time;
		
	}
	
	/* Check if this user is authorised to view this board */
	public function isAuthorised($account_id) {
		
		/* Generate the account */
		if(isset($account_id)) {
			
			$account = new account($account_id);
			
			/* Get this account's officer status */
			$officer_status = $account->isOfficer();
			
		} else {
		
			$officer_status = false;
		
		}
			
		/* Check if this board is for officers only */
		if($this->isOfficerOnly() && $officer_status == true) {
			
			/* If it's dropped into here it means the user is authorised to view this board */
			return true;
			
		} elseif($this->isOfficerOnly() == false) {
			
			/* If it's dropped into here it means the user is authorised to view this board */
			return true;
			
		} else {
			
			/* if it's dropped into here it means the user is NOT authorised to view this board */
			return false;
			
		}
		
	}
	
	/* Check if this board is for officers only */
	public function isOfficerOnly() {
		
		/* Switch through the officer only statuses */
		switch($this->officers_only) {
			
			/* No it isn't */
			case 0:
				return false;
				break;
				
			/* Yes it is */
			case 1:
				return true;
				break;
			
		}
		
	}
	
	/* Check if this user is allow to create threads */
	public function canCreateThread($account_id) {
		
		/* Check if this is the applications board */
		if($this->id == 1) {
		
			/* Return false */
			return false;
		
		}
		
		/* Check if the board is locked */
		elseif($this->isLocked()) {
						
			/* Generate the account */
			$account = new account($account_id);
			
			/* Check if they're either an officer or they're a moderator */
			if($account->isOfficer() || $account->isModerator()) {
				
				/* Yes they are */
				return true;
				
			} else {
				
				/* No they're not */
				return false;
				
			}
			
		} else {
			
			/* The board isn't locked, go ahead */
			return true;
			
		}
		
		
	}
	
	
	/* Check if this board is locked */
	public function isLocked() {
		
		/* Switch through the locked statuses */
		switch($this->locked) {
			
			/* No it isn't */
			case 0:
				return false;
				break;
				
			/* Yes it is */
			case 1:
				return true;
				break;
			
		}
		
	}
	
	/* Set Name */
	public function setTitle($new_name) {
		
		/* Create a database connection */
		$db = db();
		
		/* Escape the new name */
		$new_name = $db->real_escape_string($new_name);
		
		/* Update the database */
		$db->query("UPDATE `forum_boards` SET `name` = '$new_name' WHERE `id` = ". $this->id);
		
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
		
	}
	
	/* Set Description */
	public function setDescription($new_description) {
		
		/* Create a database connection */
		$db = db();
		
		/* Escape the new name */
		$new_description = $db->real_escape_string($new_description);
		
		/* Update the database */
		$db->query("UPDATE `forum_boards` SET `description` = '$new_description' WHERE `id` = ". $this->id);
		
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
		
	}
	
	/* Set officer only status */
	public function setOfficerOnly($new_status) {
		
		/* Create a database connection */
		$db = db();
		
		/* Switch through to see what the "real" answer is */
		if(isset($new_status)) {
			
			$final_status = 1;
			
		} else {
			
			$final_status = 0;
			
		}
		
		/* Update the database */
		$db->query("UPDATE `forum_boards` SET `officers_only` = $final_status WHERE `id` = ". $this->id);
		
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
		
	}
	
	/* Set locked status */
	public function setLocked($new_status) {
		
		/* Create a database connection */
		$db = db();
		
		/* Switch through to see what the "real" answer is */
		if(isset($new_status)) {
			
			$final_status = 1;
			
		} else {
			
			$final_status = 0;
			
		}
		
		/* Update the database */
		$db->query("UPDATE `forum_boards` SET `locked` = $final_status WHERE `id` = ". $this->id);
		
	}
	
	/* Get highest order number */
	public static function countBoards() {
		
		/* Create a database connection */
		$db = db();
		
		/* Query the database to get the list of boards */
		$result = $db->query("SELECT `order` FROM `forum_boards` ORDER BY `order` DESC LIMIT 0, 1");
		
		/* Create an object from the result set */
		$board = $result->fetch_object();
		
		/* Free the result set */
		$result->close();
		
		/* Close the database connection */
		$db->close();
		
		/* Return the order number */
		return $board->order;
		
	}
	
	/* Set new order */
	public function setOrder($new_order) {
		
		/* Create a database connection */
		$db = db();
		
		/* Update the database */
		$db->query("UPDATE `forum_boards` SET `order` = $new_order WHERE `id` = ". $this->id);
		
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
		
	}
	
}
?>