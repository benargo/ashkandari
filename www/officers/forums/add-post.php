<?php

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: /account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

/* Create a database connection */
$db = db();

/* Calculate the officer only status */
if(isset($_POST['officers_only'])) {
	
	$officers_only = 1;
	
} else {
	
	$officers_only = 0;
	
}

/* Calculate the locked status */
if(isset($_POST['locked'])) {
	
	$locked = 1;
	
} else {
	
	$locked = 0;
	
}

/* Escape the title and description */
$title = $db->real_escape_string($_POST['title']);
$desc = $db->real_escape_string($_POST['description']);

/* Post the new forum board */
$db->query("INSERT INTO `forum_boards` (`name`, `description`, `officers_only`, `locked`) VALUES ('$title', '$desc', $officers_only, $locked)") or die($db->error);

/* Get the inserted ID of this new board */
$board = new forum_board($db->insert_id);

/* Get the highest order number from the database */
$init_count = forum_board::countBoards();

/* Set the order to be the highest number + 1 */
$count = $init_count + 1;

/* And save this in the database */
$board->setOrder($count);

/* Set the messages */
$_SESSION['msg'] = "Forum board '". $_POST['title'] ."' added.";
$_SESSION['msg_status'] = "success";

/* Close the database connection */
$db->close();

/* Redirect back to the forums landing page */
header("Location: /officers/forums/");