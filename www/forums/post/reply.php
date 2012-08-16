<?php

// Require the framework files
require_once('../../../framework/config.php');

/* Get the forum thread we came from */
$thread_id = $_POST['thread'];

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=/forums/thread/". $thread_id);
	
}

/* Create a database connection */
$db = db();

/* Post the new forum board */
$db->query("INSERT INTO `forum_posts` (`thread_id`, `author_account_id`, `body`, `timestamp`) VALUES (". $_POST['thread'] .", ". $_SESSION['account'] .", '". $db->real_escape_string($_POST['body']) ."', ". time() .")") or die($db->error);

/* Get the Post ID */
$post_id = $db->insert_id;

/* Close the database connection */
$db->close();

/* Redirect back to the forums landing page */
header("Location: /forums/thread/". $thread_id);
?>