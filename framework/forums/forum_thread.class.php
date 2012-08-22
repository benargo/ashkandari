<?php
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
	
	/* Is this an application */
	public function isApplication() {
		
		/* is there an application ID */
		if(isset($this->application)) {
			
			/* Yes */
			return true;
			
		}
		
		/* No */
		return false;
		
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
	
	/* Lock the thread */
	public function lock() {
		
		/* Declare a database connection */
		$db = db();
		
		/* Query the database to update the new status */
		$db->query("UPDATE `forum_threads` SET `locked` = 1 WHERE `id` = ". $this->id);
		
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
		
	}
	
	/* Unlock the thread */
	public function unlock() {
		
		/* Declare a database connection */
		$db = db();
		
		/* Query the database to update the new status */
		$db->query("UPDATE `forum_threads` SET `locked` = 0 WHERE `id` = ". $this->id);
		
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
		
	}
	
	/* Check if this topic is sticky */
	public function isSticky() {
		
		if($this->sticky == 1) {
			
			return true;
			
		}
		
		return false;
		
	}
	
	/* Set the topic as sticky */
	public function setSticky() {
		
		/* Declare a database connection */
		$db = db();
		
		/* Query the database to update the new status */
		$db->query("UPDATE `forum_threads` SET `sticky` = 1 WHERE `id` = ". $this->id);
		
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
		
	}
	
	/* Remove sticky status */
	public function removeSticky() {
		
		/* Declare a database connection */
		$db = db();
		
		/* Query the database to update the new status */
		$db->query("UPDATE `forum_threads` SET `sticky` = 0 WHERE `id` = ". $this->id);
		
		/* Close the database connection */
		$db->close();
		
		/* And return true */
		return true;
		
	}
	
	/* Create a new forum thread */
	public static function create($board, $author, $title, $body) {
		
		/* Declare a database connection */
		$db = db();
		
		/* Escape the title */
		$title = $db->real_escape_string($title);
		
		/* Generate the time */
		$time = time();
		
		/* Insert into the database */
		$db->query("INSERT INTO `forum_threads` (`board_id`, `author_account_id`, `title`, `most_recent_post_time`) VALUES ($board, $author, '$title', $time)") or die($db->error);
		
		/* Get the thread's ID */
		$thread_id = $db->insert_id;
		
		/* Create the primary post */
		forum_post::create($thread_id, $author, $body);
		
		/* And return the thread ID */
		return $thread_id;
		
		
	}
	
}
?>