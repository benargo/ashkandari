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

/* Check if there's a board ID */
if(empty($_POST['board_id'])) {
	
	/* Redirect to the forum home page */
	header("Location: /forums/");
	
}

/* Create the forum board */
$board = new forum_board($_POST['board_id']);

/* Set the page title */
$page_title = "New Thread - ". $board->title;

/* Get the header */
require(PATH.'framework/head.php');

/* Is this board locked? */
if(!$board->isLocked() || $account->isOfficer() || $account->isModerator()) {
	
	/* No, great! Let's continue! */
	?><h1>New Thread</h1>
	
	<form action="/forums/thread/create.php" method="post">
	
		<p id="required">= Required</p>
	
		<input type="hidden" name="board_id" value="<?php echo $board->id; ?>" />
		
		<label for="title" class="required"><p>Title</p>
		<input type="text" name="title" required="true" maxlength="128" /></label>
		
		<label for="body" class="required"><p>Body</p>
		<textarea name="body" required="true" rows="5"></textarea></label>
		
		<?php /* Check if the user is an officer or a moderator */
		if($account->isOfficer() || $account->isModerator()) {
			
			/* Yes they are, print out the Locked and Sticky options */
			?><label for="locked"><p><input type="checkbox" name="locked" /> Should this thread be locked? (Only officers and moderators can post replies)</p></label>
			<label for="sticky"><p><input type="checkbox" name="sticky" /> Should this thread be sticky? (It will appear at the top of the thread list)</p></label><?php
			
		} ?>
		
		<p class="text center"><input type="submit" value="Create Thread" /></p>
	
	</form>
	<script type="text/javascript"><!--
	(function($) {
		$(document).ready(function() {
			$('#wysiwyg').wysiwyg();
			$('textarea').autosize();  
		});
	})(jQuery);
	--></script><?php
	
} else {

	/* Oh dear the forum board is locked */
	?><h1>Board Locked</h1>
	
	<p class="error">Sorry, but the board you're trying to create a thread into is locked.</p>
	
	<p><a href="/forums/<?php echo $board->id; ?>">Return to <?php echo $board->title; ?></a></p><?php

}

/* Include the footer */
require(PATH.'framework/foot.php');