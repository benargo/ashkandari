<?php
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
	
	/* Create the post */
	public static function create($thread, $author, $body) {
		
		/* Declare a database connection */
		$db = db();
		
		/* Escape the body */
		$body = $db->real_escape_string($body);
		
		/* Generate the time */
		$time = time();
		
		/* Run the database query */
		$db->query("INSERT INTO `forum_posts` (`thread_id`, `author_account_id`, `body`, `timestamp`) VALUES ($thread, $author, '$body', $time)");
		
		/* And return the post ID */
		return true;
		
	}
	
}
?>