<?php /* apply/index.php */

/* 
 * This is the page where guild members can apply to the guild
 * It involves a lot of interaction with battle.net
 */
 
// Switch HTTPS on
if( empty($_SERVER['HTTPS']) ) {
	header('Location: https://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework
require_once('../../framework/config.php');

// Set the page title
$page_title = "Guild Application";

// Require the page header
require(PATH.'framework/head.php');

?><h1>Apply to join Ashkandari</h1>

<p>We're so thrilled you want to join us here at Ashkandari. In order for us to process your application, we need to learn a little bit about you. Our application form gathers data from Battle.net in order to reduce the work load for you, and for us.</p>

<form id="form1" action="/apply/verify" method="post">

	<p>What realm is your character currently on?</p>
	<p><select name="realm" id="realms">
		<option value=""> </option><?php
		
		$realms = config::getAllRealms();
			
		while( $realm = $realms->fetch_object() ) {
				
			?><option value="<?php echo $realm->id; ?>"><?php echo $realm->name; ?></option><?php
					
		}
			
		?></select></p>
		
	<p>What is your character's name? (It needs to be exact, including any accents)</p>
	<p><input type="text" name="character" maxlength="12" placeholder="Character" required="true" /></p>
	
	<p>What is your email address?</p>
	<p><input type="email" name="email" placeholder="Email Address" required="true"></p>
	
	<p><small>Your email address is required so that we can contact you with the progress of your application. Your email address is stored in our databases and officers cannot see it directly. For further information please read our <a href="/legal/privacy">privacy policy</a>.</small></p>
	
	<p>When you're ready, click Continue. It may take a few minutes to fetch and verify your character from Battle.net, so please be patient and do not refresh the page.</p>
	
	<p><input id="submit" type="submit" value="Continue" /></p>

</form><?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>