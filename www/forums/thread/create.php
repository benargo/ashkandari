<?php

// Require the framework files
require_once('../../../framework/config.php');

/* Create an instance of the forum board */
$board = new forum_board($_POST['board_id']);

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=/forums/". $board->id);
	
}

/* Create a new account */
$account = new account($_SESSION['account']);

/* Create the new forum thread */
$thread_id = forum_thread::create($board->id, $account->id, $_POST['title'], $_POST['body']);

/* Get an instance of this new forum thread */
$thread = new forum_thread($thread_id);

/* Now check if it's supposed to be locked */
if(isset($_POST['locked'])) {
	
	/* Yes it is, lock the thread */
	$thread->lock();
	
}

/* Now check if it's supposted to be sticky */
if(isset($_POST['sticky'])) {
	
	/* Yes it is, make the thread sticky */
	$thread->setSticky();
	
}

/* Redirect back to the forums landing page */
header("Location: /forums/thread/". $thread->id);
?>