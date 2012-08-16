<?php
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the head of the page
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

/* Get the new forum thread */
$thread = new forum_thread($_POST['thread_id']);

/* Switch through the possible values */
switch($_POST['action']) {
	
	/* Set Sticky */
	case "Make Sticky":
		$thread->setSticky();
		break;
	
	/* Remove Sticky */
	case "Remove Sticky":
		$thread->removeSticky();
		break;
	
}

/* Return to the thread */
header("Location: /forums/thread/". $thread->id);

?>