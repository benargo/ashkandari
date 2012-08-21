<?php

// Require the head of the page
require_once('../../../framework/config.php');

/* Create an instance of a new post */
$post = new forum_post($_POST['post_id']);

/* Get the thread from the post */
$thread = $post->getThread();

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=/forums/thread/". $thread->id);
	
}

/* Set page title */
$page_title = "Edit Post - ". $thread->title;

/* Require the page head */
require(PATH.'framework/head.php');

/* Check if we're allowed to edit this */
if($post->isEditable($account->id)) {
	
	?><h1>Edit Post</h1>
	
	<form action="/forums/post/edit-post.php" method="post">
	
		<input type="hidden" name="post_id" value="<?php echo $post->id; ?>" />
		
		<label for="body"><textarea name="body" class="tinymce" required="true" rows="10"><?php echo $post->body; ?></textarea></label>
		
		<p class="text center"><input type="submit" value="Save Post" /></p>
		
	</form><script type="text/javascript"><!--
	(function($) {
		$(document).ready(function() {
			$('textarea').autosize();  
		});
	})(jQuery);
	--></script><?php
	
}

/* Require the footer */
require(PATH.'framework/foot.php');