<?php

/* This page controls the classes required to make the forums operational.
 * It includes a generic function to get all the forum boards, and then
 * 4 classes to allow us to handle the three layered components:
 *
 * 1. Category
 * 2. Boards
 * 3. Threads
 * 4. Posts
 */
 
/* Get all forum boards */
function getAllBoards() {
	
	/* Define a database connection */
	$db = db();
	
	/* Get all the categories from the database */
	$result = $db->query("SELECT `id` FROM `forum_boards` ORDER BY `order`");
	
	/* Close the database connection */
	$db->close();
	
	/* And return the result set */
	return $result;
	
}

/* Get all non-officer boards */
function getAllNonOfficerBoards() {
	
	/* Define a database connection */
	$db = db();
	
	/* Get all the categories from the database */
	$result = $db->query("SELECT `id` FROM `forum_boards` WHERE `officers_only` = 0");
	
	/* Close the database connection */
	$db->close();
	
	/* And return the result set */
	return $result;
	
}

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
		$account = new account($account_id);
		
		/* Get this account's officer status */
		$officer_status = $account->isOfficer();
		
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

class forum_thread {
	
	/* Variables */
	public $id;
	private $board;
	private $author;
	private $application;
	public $title;
	private $latest_post;
	public $update_time;
	private $locked;
	private $sticky;
	
	/* Construction function */
	public function __construct($id) {
		
		/* Create a database connection */
		$db = db();
		
		/* Get the thread from the database */
		$result = $db->query("SELECT * FROM `forum_threads` WHERE `id` = $id LIMIT 0, 1");
		
		/* Create an object from the result set */
		$thread = $result->fetch_object();
		
		/* Free the result set */
		$result->close();
		
		/* Set varaibles */
		$this->id = $thread->id;
		$this->board = $thread->board_id;
		$this->author = $thread->author_account_id;
		$this->application = $thread->application_id;
		$this->title = $thread->title;
		
		/* Set the most recent post */
		$this->setLatestPost();
		
		/* Set more variables */
		$this->update_time = $thread->most_recent_post_time;
		$this->locked = $thread->locked;
		$this->sticky = $thread->sticky;
		
		/* Close the database connection */
		$db->close();
		
	}
	
	/* Get the board object */
	public function getBoard() {
		
		/* Create the new board object */
		$board = new forum_board($this->board);
		
		/* And return it */
		return $board;
		
	}
	
	/* Get the author object */
	public function getAuthor() {
	
		if(isset($this->author)) {
						
			/* Create the new account object */
			$author = new account($this->author);
		
			/* And return it */
			return $author;
		
		}
		
	}
	
	/* Get the author's character */
	public function getCharacter() {
		
		if(isset($this->author)) {
		
			/* Create the new account object */
			$author = $this->getAuthor();
			
			/* Create the new character object */
			$character = $author->getPrimaryCharacter();
			
			/* And return it */
			return $character;
		
		}	
			
	}
	
	/* Get the application */
	public function getApplication() {
		
		/* Create the new application object */
		$application = new application($this->application);
		
		/* And return it */
		return $application;
		
	}
	
	/* Count the number of posts */
	public function countPosts() {
		
		/* Create a database connection */
		$db = db();
		
		/* Get the posts from the database */
		$result = $db->query("SELECT `id` FROM `forum_posts` WHERE `thread_id` = ". $this->id);
		
		/* Count the number of rows */
		$count = $result->num_rows;
		
		/* Close the result set */
		$result->close();
		
		/* Close the database connection */
		$db->close();
		
		/* Return the count */
		return $count;
		
	}
	
	/* Get all available posts */
	public function getPosts() {
		
		/* Create a database connection */
		$db = db();
		
		/* Get the posts from the database */
		$result = $db->query("SELECT `id` FROM `forum_posts` WHERE `thread_id` = ". $this->id ." ORDER BY `timestamp`");
		
		/* Close the database connection */
		$db->close();
		
		/* And return the result set */
		return $result;
		
	}
	
	/* Get the latest post */
	public function getLatestPost() {
		
		/* Create a post object from the ID */
		$post = new forum_post($this->latest_post);
		
		/* And return it */
		return $post;
		
	}
	
	/* Set the most recent post - used by __construct */
	private function setLatestPost() {
		
		/* Create a database connection */
		$db = db();
		
		/* Get the post from the database */
		$result = $db->query("SELECT * FROM `forum_posts` WHERE `thread_id` = ". $this->id ." ORDER BY `timestamp` DESC LIMIT 0, 1");
		
		/* Create an object from the result set */
		$post = $result->fetch_object();
		
		/* Free the result set */
		$result->close();
		
		/* Set the most recent post for this thread in the database */
		$this->latest_post = $post->id;
		
		/* Update the database to reflect this */
		$db->query("UPDATE `forum_threads` SET `most_recent_post_time` = ". $post->timestamp ." WHERE `id` = ". $this->id);
		
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
		
	}
	
	/* Get the time of the most recent post */
	public function getUpdateTime() {
		
		return date('j M Y H:i:s', $this->update_time);
		
	}
	
	
	/* Check if this topic is locked */
	public function isLocked() {
		
		if($this->locked == 1) {
			
			return true;
			
		}
		
		return false;
		
	}
	
	/* Check if this topic is locked */
	public function isNotLocked() {
		
		if($this->locked == 0) {
			
			return true;
			
		}
		
		return false;
		
	}
	
	/* Check if this topic is sticky */
	public function isSticky() {
		
		if($this->sticky == 1) {
			
			return true;
			
		}
		
		return false;
		
	}
	
}

class forum_post {
	
	/* Variables */
	public $id;
	private $thread;
	private $author;
	public $body;
	public $timestamp;
	public $edit_time;
	private $editor;
	
	/* Construction function */
	public function __construct($id) {
		
		/* Declare a database connection */
		$db = db();
		
		/* Select the post from the database */
		$result = $db->query("SELECT * FROM `forum_posts` WHERE `id` = $id LIMIT 0, 1");
		
		/* Create an object from the result set */
		$post = $result->fetch_object();
		
		/* Free the result set */
		$result->close();
		
		/* Set variables */
		$this->id = $post->id;
		$this->thread = $post->thread_id;
		$this->author = $post->author_account_id;
		$this->body = $post->body;
		$this->timestamp = $post->timestamp;
		$this->edit_time = $post->edited_time;
		$this->editor = $post->edited_account_id;
		
	}	
	
	/* Get the thread */
	public function getThread() {
		
		/* Create a new instance of the thread object */
		$thread = new forum_thread($this->thread);
		
		/* And return it */
		return $thread;
		
	}
	
	/* Get the account */
	public function getAccount() {
		
		/* Create a new instance of the account object */
		$account = new account($this->author);
		
		/* And return it */
		return $account;
		
	}
	
	/* Get the character */
	public function getCharacter() {
		
		/* Get a new instance of the account object */
		$account = $this->getAccount();
		
		/* Now get the character object */
		$character = $account->getPrimaryCharacter();
		
		/* And return it */
		return $character;
		
	}
	
	/* Check if this post is editable */
	public function isEditable($account_id) {
		
		/* Create the new user account */
		$account = new account($account_id);
		
		/* Check if they have permission to edit this */
		if($account->id == $this->author || $account->isOfficer() | $account->isModerator()) {
			
			/* Either:
			 * They are the owner,
			 * They are an officer,
			 * They are a moderator */
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	/* Get the editor's account */
	public function getEditor() {
		
		/* Get the account */
		$account = new account($this->editor);
		
		/* Return it */
		return $account;
		
	}
	
	/* Get the time the post was edited and return it, formatted */
	public function getEditTime() {
		
		/* Just return it pre-formatted */
		return date('j M Y H:i:s', $this->edit_time);
		
	}
	
	/* Update the content */
	public function edit($new_body, $account_id) {
		
		/* Create a database instance */
		$db = db();
		
		/* Escape the body content */
		$body = $db->real_escape_string($new_body);
		
		/* Generate the update time */
		$time = time();
		
		/* Update the database */
		$db->query("UPDATE `forum_posts` SET `body` = '$body', `edited_time` = $time, `edited_account_id` = $account_id WHERE `id` = ". $this->id);
		
		/* Close the database connection */
		$db->close();
		
		/* And return out */
		return true;
		
	}
	
	/* Delete the post */
	public function delete($account_id) {
	
		/* Create an instance of this account */
		$account = new account($account_id);
		
		/* Check if we're authorised to run this deletion */
		if($account->id == $this->author || $account->isOfficer() || $account->isModerator()) {
		
			/* Create a database instance */
			$db = db();
			
			/* Delete the post */
			$db->query("DELETE FROM `forum_posts` WHERE `id` = ". $this->id);
			
			/* Close the database connection */
			$db->close();
			
		}
		
	}
	
}


