<?php
// 8. News comment class
class news_comment {
	
	/* This class provides us with the means of generating all the comments for a specific news article */
	
	// Variables
	private $id;
	private $news_item_id;
	private $author_id;
	private $in_reply_to_id;
	private $date;
	public $content;
	
	public function __construct($comment_id) {
		
		// This function constructs the news_comment object
		
		// First, instanciate a new object of the database
		$db = db();
		
		// Next, run the query to select this particular comment from the database
		if ( $result = $db->query("SELECT * FROM `news_comments` WHERE `id` = $comment_id LIMIT 0, 1") ) {
			
			// If we dropped into here, fantastic, it means we were able to find the comment in question
			// Let's create an object from the result
			$obj = $result->fetch_object();
			
			// Now we can free the result set
			$result->close();
			
			// And close the database connection as well
			$db->close();
			
			// Let's start setting the objects variables
			$this->id = $obj->id;
			$this->news_item_id = $obj->news_item_id;
			$this->author_id = $obj->author_account_id;
			$this->in_reply_to_id = $obj->comment_in_reply_to_id;
			$this->date = $obj->date_published;
			$this->content = $obj->content;
			
			// And finally return true
			return true;
			
		} else {
			
			// Oh dear, we weren't able to get that specific comment. Something must have gone wrong.
			// Close the database connection
			$db->close();
			
			// And return out false
			return false;
			
		}
		
	}
	
	public function getAuthor() {
		
		// This function gets the author's primary character and returns the character ID
		// which we can use to create a character object
		
		// Create a new account object based on the account ID of this object's instance
		$account = new account($this->author_id);
		
		// Return the ID number of the primary character
		return $account->primary_character;
		
	}
	
	public function getDate() {
		
		// Calculate the formatted date
		$formatted_date = date('jS F Y', $this->date);
		
		return $formatted_date;
		
	}
	
	public function getTime() {
		
		// Calculate the formatted time
		$formatted_time = date('H:i T', $this->date);
		
		// Return the formatted time
		return $formatted_time;
		
	}
	
	public function getChildComments() {
		
		// This function gets any child comments and returns their ID numbers in an array
		
		// First instanciate a new database object
		$db = db();
		
		// Query the database to find the comments we want
		if( $result = $db->query("SELECT `id` FROM `news_comments` WHERE `comment_in_reply_to_id` = ". $this->id ." ORDER BY `date_published`") ) {
			
			// If we dropped into here, fantastic, it means we found some child comments.
			// Fetch an array from the result set
			$array = $result->fetch_array();
			
			// Now free up the result set
			$result->close();
			
			// And close the database connection
			$db->close();
			
			// Finally, return the array we just generated
			return $array;
			
		} else {
			
			// If we dropped into here, not to worry, it just means there are no more child comments
			// So we first close the database connection
			$db->close();
			
			// And return out false
			return false;
			
		}
		
	}
}
?>