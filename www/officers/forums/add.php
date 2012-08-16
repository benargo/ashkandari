<?php
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

// Set the page title
$page_title = "Add Forum board - Officer Camp";

// Require the head of the document
require(PATH.'framework/head.php'); 

if($account->isOfficer()) {
	
	?><h1>Add Forum Board</h1>

<nav class="officer">
	<?php include(PATH.'framework/officer_nav.php'); ?>
</nav>

<form action="/officers/forums/add-post.php" method="post">
	
	<p>Adding a new forum board is easy. Just start filling out the neccessary information below.</p>
	
	<label for="title" class="required"><p>Set a title for this board.</p>
	<input type="text" name="title" required="true" maxlength="128" /></label>
	
	<label for="description" class="required"><p>Describe the contents of this board.</p>
	<textarea name="description" rows="3"></textarea></label>
	
	<label for="officers_only"><p><input type="checkbox" name="officers_only" /> Should this board be for officers only?</p></label>
	
	<label for="locked"><p><input type="checkbox" name="locked" /> Should this board be locked? (only officers and moderators can create threads)</p></label>
	
	<p class="text center"><input type="submit" value="Add Board" /></p>
		
</form>

<?php

} else {
	
	header("HTTP/1.1 403 Forbidden");
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>