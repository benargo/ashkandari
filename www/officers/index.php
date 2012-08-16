<?php // officers/index.php

/* 
 * This is the login page for the Ashkandari website. It has two versions available to it.
 * One of them being to provide the login form, and the other one being the ability to process
 * the posted login information to us.
 * Which one we use is determined by the presence of $_POST variables.
 */
 
// Switch HTTPS on
if( empty($_SERVER['HTTPS']) ) {
	header('Location: https://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

// Set the page title
$page_title = "Officer Camp";

// Require the head of the document
require(PATH.'framework/head.php'); 

if($account->isOfficer()) {
	
	?><h1>Officer Camp</h1>

<p>Welcome <?php echo $primary_character->name; ?>, to the Officer Camp.</p>
<p>As an officer you can control many parts of this website and its primary functions and uses.</p>
<p>Use the links below to add, update and remove various items.</p>

<nav class="officer">
	<?php include(PATH.'framework/officer_nav.php'); ?>
</nav>

<?php

} else {
	
	header("HTTP/1.1 401 Unauthorized");
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>