<?php /* account/characters/claim-verify.php */

/* 
 * This is the page where we post variables that allow us to claim a new character
 */

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
		header("Location: /account/login?ref=/account/");
	
}

// Set the page title
$page_title = "Verify your Character";

// Require the head of the document
require(PATH.'framework/head.php');

/* Create an account object */
$account = new account($_SESSION['account']);

/* Now we can get the character from the database */
$character = new character($_POST['character']);

/* Run the verification on the character */
if( $character->verify($_POST['slot1'], $_POST['slot2']) ) {

	/* Create a DB connection */
	$db = db();

	/* Change the value in the characters table to reflect this new claim of ownership */
	$db->query("UPDATE `characters` SET `account_id` = ". $account->id ." WHERE `id` = ". $character->id);

	if(!$account->getPrimaryCharacter()) {
		$account->setPrimaryCharacter($character->id);
	}
	
	/* Can close the database connection */
	$db->close();

	// Set a cookie variable containing their account ID
	setcookie('account', encrypt($account->id), time()+60*60*24, '/');
	
	?><h1>Character Verified</h1>
	
	<p>Hey <?php echo $character->name; ?>!</p>
		
	<p>Thanks for doing that. I know it's a bit of a hastle but we need to be certain that you really are who you say you are.</p>
		
	<p>We're all done here. Your character has been added to your account.</p>
	
	<p><a href="/account/characters/set-primary/<?php echo $character->id; ?>" class="button" title="Set this character to be your primary one">Set as Primary Character</a> 
		<a href="/account/" class="button" title="Return to your account overview">Back to My Account</a></p><?php

} else {

	/* If it's dropped into here it means that this particular character has already been claimed */
	?><h1>Unable to Claim Character</h1>
	
	<p>Sorry, but <?php echo $character->name; ?> has already been claimed and verified as being owned by somebody else. Please try entering another character name and trying again.</p>
	
	<form action="/account/register/verify" method="post">
	
		<input type="hidden" name="email" value="<?php echo $_POST['email']; ?>" />
		<input type="hidden" name="password" value="<?php echo $_POST['password']; ?>" />
		
		<p>Please retype your character name, including any accents. The system should recognise your characters name and just select it from the list that forms.</p>
		<p><select name="character" id="characters">
			<option value=""> </option><?php
			
			/* Get all the characters */
			$characters = character::getAllCharacters();
				
			while( $char = $characters->fetch_object() ) {
					
				?><option value="<?php echo $char->id; ?>"><?php echo $char->name; ?></option><?php
						
			}
				
			?></select></p>
			
		<p>If your character is not showing up correctly, it may mean the guild roster is slightly out of date. The roster is automatically updated every morning at 23:00 UTC so please try again after that.</p>
	
		<p>When you're ready, click Continue. It may take a few minutes to fetch and verify your character from Battle.net, so please be patient and do not refresh the page.</p>
		<p><input id="submit" type="submit" value="Continue" /></p>
	
	</form><?php

}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>