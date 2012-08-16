<?php

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

/* Get all the boards */
$boards = getAllBoards();

/* Loop through all the boards */
while($b = $boards->fetch_object()) {
				
	/* Create a new object for each board */
	$board = new forum_board($b->id);

	/* Get the ID number from the board */
	$id = $board->id;

	/* Get the new order */
	$new_order = $_POST['order_'. $id];
	
	/* And set the new order */
	$board->setOrder($new_order);
}

/* We're done, set the success messages */
$_SESSION['msg'] = "Order updated.";
$_SESSION['msg_status'] = "success";

/* Redirect to the forums home page */
header("Location: /officers/forums/");

?>