<?php // officers/applications.php

/* 
 * This is the page for Ashkandari's officer section where officers can review outstanding applications.
 * It also includes links for officers to review previously accepted or declined applications.
 */
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://www.ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: /account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

// Set the page title
$page_title = "EPGP Standings - Officer Camp";

// Require the head of the document
require(PATH.'framework/head.php'); 

if($account->isOfficer()) {
	
	?><h1>EPGP Standings</h1>

<nav class="officer">
	<?php include(PATH.'framework/officer_nav.php'); ?>
</nav>

<p>You can update the EPGP standings by posting the content from the EPGP addon here.</p>

<form action="https://ashkandari.com/officers/epgp/update.php" method="post">

	<p><textarea name="epgp" rows="5"></textarea></p>
	<p><input type="submit" value="Update" /></p>
	
</form>

	<script type="text/javascript"><!--
	$(function() {
		$("textarea").autosize();
	});
	--></script>

<?php

} else {
	
	header("HTTP/1.1 401 Unauthorized");
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>