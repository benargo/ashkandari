<?php
class news_item {
	
	/* This class provides us with the means of displaying the news items and appropriate comments */
	
	// Variables
	public $id;
	private $author_id;
	private $date;
	public $title;
	public $content;
	private $comments_allowed;
	
	public function __construct($news_item_id) {
		
		// First, set up a new instance of the database
		$db = db();
		
		// Query the database to see if we can find the news item by that ID
		if( $result = $db->query("SELECT * FROM `news_items` WHERE `id` = $news_item_id LIMIT 0, 1") ) {
			
			// Fetch an object based on the result
			$news_item = $result->fetch_object();
			
			// Free the result set
			$result->close();
			
			// Close the database connection
			$db->close();
			
			// Now we can start setting the variables
			$this->id = $news_item->id;
			$this->author_id = $news_item->author_account_id;
			$this->date_published = $news_item->date_published;
			$this->title = $news_item->title;
			$this->content = $news_item->content;
			$this->comments_allowed = $news_item->comments_allowed;
		
			// Return as true
			return true;
			
		} else {
		
			// Call the destruction function to close down the instance of this class
			$this->__destruct();
			
			// Close the database connection
			$db->close();
			
			// Return as false
			return false;
			
		}
		
	}
	
	public static function getArticles($limit = 10, $start = 0) {
		
		// First, set up a new instance of the database
		$db = db();
		
		// Query the database to see if we can find the news items based on the values provided
		if ( $result = $db->query("SELECT `id` FROM `news_items` ORDER BY `date_published` LIMIT $start, $limit") ) {
			
			// Fetch an array based on the result
			$array = $result->fetch_array();
			
			// Free the result set
			$result->close();
			
			// Close the database connection
			$db->close();
			
			// Return the array as a result of the function
			return $array;
			
		} else {
			
			// If we've dropped into here it means that something went wrong
			// So close the database connection
			$db->close();
			
			// And return out false
			return false;
			
		}
		
	}
	
	public function getAuthor() {
		
		// This function gets the author's primary character and returns the character ID 
		// which we can use to create a character object

		// Create a new account object based on the account ID of this news instance
		$account = new account($this->author_id);
		
		// Return the ID of the primary character
		return $account->primary_character;
		
	}
	
	public function getDate() {
		
		// Calculate the formatted date
		$formatted_date = date('jS F Y', $this->date);
		
		// Return the formatted date
		return $formatted_date;
		
	}
	
	public function getTime() {
		
		// Calcualte the formatted time
		$formatted_time = date('H:i T', $this->date);
		
		return $formatted_time;
		
	}
	
	public function commentsAllowed() {
		
		switch($this->comments_allowed) {
			
			case 0:
				return false;
				break;
				
			case 1:
				return true;
				break;
			
		}
		
	}
	
	public function countComments() {
		
		// This function counts the number of comments recorded for this item
		
		// First, create a new instance of the database
		$db = db();
		
		// Query the database to get the number of comments
		if( $result = $db->query("SELECT `id` FROM `news_comments` WHERE `news_item_id` = ". $this->id) ) {
			
			// If we've dropped into here then that means we're on the right track
			$rows = $result->num_rows;
			
			// Free the result set
			$result->close();
			
			// Close the database connection
			$db->close();
			
			// And return the number of rows
			return $rows;
			
		} else {
			
			// Oh dear, there are no comments.
			// Close the database connection
			$db->close();
			
			// And return out 0
			return 0;
			
		}
		
	}
	
	public function getComments() {
		
		// Get all the comments related to this article
		// However, in this circumstance we only want to get the comments that are parents, 
		// not children of other comments as these are called elsewhere in the application.
		
		// Create a new instance of the database
		$db = db();
		
		// Query the database to get the ID numbers of all the comments for this article
		if( $result = $db->query("SELECT `id` FROM `news_comments` WHERE `news_item_id` = ". $this->id ." AND `comment_in_reply_to_id` IS NULL ORDER BY `date_published`") ) {
			
			// If we've dropped in here it means we were able to do this
			
			// Create an array from the result set
			$array = $result->fetch_array();
			
			// Free the result set
			$result->close();
			
			// Close the database connection
			$db->close();
			
			// Return the array as the output from this function
			return $array;
			
		} else {
			
			// Oh dear, something went wrong
			
			// Close the database connection
			$db->close();
			
			// And return out false
			return false;
			
		}
		
	} 
}
?>