<?php

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

/* Create a database connection */
$db = db();

/* Set up the category object */
$board = new forum_board($_GET['id']);

/* Delete the forum category */
if($db->query("DELETE FROM `forum_boards` WHERE `id` = ". $board->id)) {

	/* Set the messages */
	$_SESSION['msg'] = "Forum board '". $board->title ."' deleted.";
	$_SESSION['msg_status'] = "success";

} else {
	
	/* Set the messages */
	$_SESSION['msg'] = "Failed to delete board";
	$_SESSION['msg_status'] = "error";
	
}

/* Close the database connection */
$db->close();

/* Redirect back to the forums landing page */
header("Location: /officers/forums/");