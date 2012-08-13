<?php

/* This page controls the class for applying characters
 * It contains a set of primary functions that will get
 * all the applications of a certain type.
 * The remainder is a fully functioning object oriented
 * class that represents an individual application. */
 
function getApplications($type = NULL) {
	
	/* This function gets all the applications from the database
	 * By switching through the type that's requested from the function */
	 
	/* Declare a database instance */
	$db = db();
	
	/* Switch through the available types */
	switch($type) {
		
		/* Outstanding applications */
		case "outstanding":
			$result = $db->query("SELECT * FROM `applications` WHERE `decision` IS NULL");
			break;
			
		case "accepted":
			$result = $db->query("SELECT * FROM `applications` WHERE `decision` = 1");
			break;
			
		case "declined":
			$result = $db->query("SELECT * FROM `applications` WHERE `decision` = 0");
			break;
			
		default:
			$result = $db->query("SELECT * FROM `applications`");
			break;	
		
	}
	
	/* Close the database connection */
	
	$db->close();
	return $result;
	
}

class applying_character {

	/* Variables */
	private $id;
	private $player;
	public $name;
	private $realm;
	private $class;
	private $race;
	private $gender;
	private $level;
	private $achievementPoints;
}