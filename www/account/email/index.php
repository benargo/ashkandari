<?php // account/email.php

/* 
 * This is the login page for the Ashkandari website. It has two versions available to it.
 * One of them being to provide the login form, and the other one being the ability to process
 * the posted login information to us.
 * Which one we use is determined by the presence of $_POST variables.
 */
 
// Switch HTTPS on
if( empty($_SERVER['HTTPS']) ) {
	header('Location: https://www.ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
		header("Location: /account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

// Set the page title
$page_title = "My Email Address &amp; Email Preferences";

// Require the head of the document
require(PATH.'framework/head.php');

?><h1>My Email Address</h1>
	
<form action="/account/email/verify" method="post">

	<p>You can change your email address at any time. Simply enter a new email address in the box below and we can verify it as soon as possible.</p>
	
	<p><input type="email" name="new_email" required="true" /></p>
	<p><input type="submit" value="Change Email Address" /></p>
	
</form>

<h1>My Email Preferences</h1>

<form action="/account/email/preferences" method="post">

	<p>Change your email preferences in order to opt in and out of available services.</p>
	
	<p><input type="checkbox" name="essential" checked="true" disabled="true" /> Essential emails, including password resets, activation emails and security updates.</p>
	
	<p><input type="checkbox" name="news" checked="<?php echo $account->subscribedNews(); ?>" /> News articles pushed to your inbox as they're published.</p>
	
	<p><input type="checkbox" name="digest" checked="<?php echo $account->subscribedDigest(); ?>" /> Weekly digest of news articles, trending forum topics and new characters.</p>
	
	<p><input type="submit" value="Save Preferences" /></p>
	
</form>
<?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>