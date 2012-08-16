<?php

// Require the framework files
require_once('../../../framework/config.php');

/* Create an instance of a new post */
$post = new forum_post($_POST['post_id']);

/* Create the thread from the post */
$thread = $post->getThread();

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=/forums/thread/". $thread->id);
	
}

/* Delete this post */
$post->delete($_SESSION['account']);

/* Return to the thread */
header("Location: /forums/thread/". $thread->id);