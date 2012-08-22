<?php

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: /account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

/* Create an instance of a new board */
$board = new forum_board($_POST['id']);

$board->setTitle($_POST['title']);
$board->setDescription($_POST['description']);
$board->setOfficerOnly($_POST['officers_only']);
$board->setLocked($_POST['locked']);

/* Set the messages */
$_SESSION['msg'] = "Forum board '". $board->title ."' updated.";
$_SESSION['msg_status'] = "success";

/* Redirect back to the forums landing page */
header("Location: /officers/forums/");