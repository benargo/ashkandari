<?php
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the head of the page
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: /account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

/* Get the new forum thread */
$thread = new forum_thread($_POST['thread_id']);

/* Switch through the possible values */
switch($_POST['action']) {
	
	/* Lock */
	case "Lock":
		$thread->lock();
		break;
	
	/* Unlock */
	case "Unlock":
		$thread->unlock();
		break;
	
}

/* Return to the thread */
header("Location: /forums/thread/". $thread->id);

?>