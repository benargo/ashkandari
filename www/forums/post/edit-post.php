<?php

// Require the framework files
require_once('../../../framework/config.php');

/* Create an instance of a new post */
$post = new forum_post($_POST['post_id']);

/* Get the thread from the post */
$thread = $post->getThread();

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=/forums/thread/". $thread->id);
	
}

/* Create a new account */
$account = new account($_SESSION['account']);

/* Set the new content */
$post->edit($_POST['body'], $account->id);

/* Redirect back to the forums landing page */
header("Location: /forums/thread/". $thread->id);