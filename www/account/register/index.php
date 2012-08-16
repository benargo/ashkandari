<?php /* account/register/index.php */

/* 
 * This is the page where guild members can register and create an account
 * on our guild website.
 */
 
// Switch HTTPS on
if( empty($_SERVER['HTTPS']) ) {
	header('Location: https://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework
require_once('../../../framework/config.php');

// Check if we're already logged in
if(isset($_SESSION['account'])) {
	
		header("Location: https://ashkandari.com/account/");
	
}

// Set the page title
$page_title = "Create an Account";

// Require the page header
require(PATH.'framework/head.php');

?><h1>Create an Account</h1>

<p>Welcome to the registration process. We should point out that this function is for existing members only. Players wishing to apply to the guild should do so through our <a href="/apply/">application section</a>. As part of the registration process you will be asked to verify a character which is <em>not</em> an alt rank.</p>

<p>The registration process is designed to be as simple as possible, taking as much data as we can get from Battle.net to reduce your workload and allow us to ensure all people creating an account are actually members of Ashkandari.</p>
	
<form id="form1" action="/account/register/verify" method="post">
	
	<p id="required">= Required</p>
	
	<label for="email" class="required"><p>To start with, we need you to enter your email address:</p>
	<input type="email" name="email" placeholder="Email Address" required="true"></label>
	
	<p>Your email address is required so that we can verify your identity and contact you with any messages and notifications you choose to receive. Your email address is stored in our databases and other players cannot see it directly. For further information please read the <a href="/legal/privacy">privacy policy</a>.</p>
	
	<label for="password" class="required"><p>Now we need you to choose a password:</p>
	<input type="password" name="password" autocomplete="no" required="true" /></label>
	
	<label for="password_verify" class="required"><p>And if you wouldn't mind, please type that password again for verification purposes:</p>
	<input type="password" name="password_verify" autocomplete="no" required="true" /></label>

	<label for="character" class="required"><p>The final bit of information we need is for you to type the name of your primary character (including any accents):</p>
	<select name="character" id="characters">
		<option value=""> </option><?php
		
		/* Get all the characters */
		$characters = character::getAllCharacters();
			
		while( $character = $characters->fetch_object() ) {
				
			?><option value="<?php echo $character->id; ?>"><?php echo $character->name; ?></option><?php
					
		}
			
		?></select></label>
		
	<p>If your character is not showing up correctly, it may mean the guild roster is slightly out of date. The roster is automatically updated every morning at 23:00 UTC so please try again after that.</p>
	
	<p>When you're ready, click Continue. It may take a few minutes to fetch and verify your character from Battle.net, so please be patient and do not refresh the page.</p>
	<p class="text center"><input id="submit" type="submit" value="Continue" /></p>

</form><?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>