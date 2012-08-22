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
?>