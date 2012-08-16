<?php

// Require the framework files
require_once('../../../framework/config.php');

/* Get the forum thread we came from */
$thread_id = $_POST['thread'];

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=/forums/thread/". $thread_id);
	
}

/* Create the new forum post */
forum_post::create($thread_id, $_SESSION['account'], $_POST['body']);

/* Redirect back to the forums landing page */
header("Location: /forums/thread/". $thread_id);
?>