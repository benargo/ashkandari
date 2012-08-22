<?php /* Ashkandari Framework */

/***************************************************
 * This is the primary configuration file for
 * Ashkandari's web application. It provides a
 * number of items, such as constant items, database
 * configuration and the classes for object oriented
 * interaction.
 *
 * Table of Contents
 * -----------------
 * 1. Pre-flight checks
 * 2. Constants
 * 3. Database integration
 * 4. Configuration functions
 * 5. Account class
 * 6. Character classes
 * 7. News classes
 * 8. Applications class
 * 9. Forum classes
 ***************************************************/

/* 1. Pre-flight checks */
// Start by turning sessions on
session_start();

// Determine the protocol (HTTP or HTTPS)
if( empty($_SERVER["HTTPS"]) ) {
	$protocol = "http";
} else {
	$protocol = "https";
}

/* 2. Constants */
define("PATH", "/home/ashkandari/"); // Base path for this server
define("BASE_URL", $protocol."://ashkandari.com"); // The URL this web application runs off

/* Salt integration - This is in a seperate file for security reasons */
require_once(PATH."framework/salt.php");

/* 3. Database integration
 * For this we need to include the database connection details.
 * This is in a seperate file for security reasons :P */
require_once(PATH."db/mysqli_connection.php");

// Database function
function db() {
	
	/* Create a new instance of our database
	 * NB: This function returns an OBJECT */

	// First, get the variables from the included file
	global $db_details;

	// Create the new MySQLi object
	$db = new mysqli($db_details["host"], $db_details["user"], $db_details["password"], $db_details["name"]);

	// And return it for our use.
	return $db;
		
}

/* 4. Configuration functions */
require_once(PATH.'framework/functions.php');

/* 5. Account class */
require_once(PATH.'framework/account.class.php');

/* 6. Guild Applications */
require_once(PATH.'framework/applications.class.php');
require_once(PATH.'framework/characters/progression.class.php');
require_once(PATH.'framework/characters/spec.class.php');

/* 7. Character classes */
require_once(PATH.'framework/characters/character.class.php');
require_once(PATH.'framework/characters/cooking.class.php');
require_once(PATH.'framework/characters/first-aid.class.php');
require_once(PATH.'framework/characters/fishing.class.php');
require_once(PATH.'framework/characters/profession.class.php');

/* 8. News class */
require_once(PATH.'framework/news_item.class.php');
require_once(PATH.'framework/news_comment.class.php');

/* 9. Forums */
require_once(PATH.'framework/forums/functions.php');
require_once(PATH.'framework/forums/forum_board.class.php');
require_once(PATH.'framework/forums/forum_thread.class.php');
require_once(PATH.'framework/forums/forum_post.class.php');
?>