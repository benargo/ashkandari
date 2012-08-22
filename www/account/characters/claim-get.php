<?php /* account/characters/claim-get.php */

/* 
 * This is the page where we post variables that allow us to claim a new character
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
$page_title = "Adding a new Character";

// Require the head of the document
require(PATH.'framework/head.php');

/* Now we can get the character from the database */
$character = new character($_GET['id']);

if(isset($character->id)) {

 	if(empty($character->account_id)) {
	
		/* If it's dropped into here it means we can take this particular character */
		
		/* Declare the two slots we're going to use for the verification */
		$slot1 = getRandomItemSlot();
		$slot2 = getRandomItemSlot($slot1->name);
		
		?><h1>Verify Your Character</h1>
		
		<p>If you don't mind we just need to verify that you really are <?php echo $character->name; ?>. We need you to remove two pieces of gear from your character and check that you have removed those two pieces. To do this you will need access to your World of Warcraft installation and be able to log in and out.</p>
		
		<p>So, the items we need you to remove are:</p>
		
		<form action="/account/characters/verify" method="post">
		
			<input type="hidden" name="character" value="<?php echo $character->id; ?>" />
			<input type="hidden" name="slot1" value="<?php echo $slot1->id; ?>" />
			<input type="hidden" name="slot2" value="<?php echo $slot2->id; ?>" />
		
			<ul>
				<li class="bold"><?php echo $slot1->name; ?></li>
				<li class="bold"><?php echo $slot2->name; ?></li>
			</ul>
		
			<p>Once you have removed those two pieces of gear, we need you to completely log out of your World of Warcraft account (i.e. shut down the game client altogether). Once you have done that you can click "Verify" below and move on to the final stage of the application process.</p>
			
			<p><input type="submit" value="Verify" /></p>
		
		</form><?php	
		
	} else {
		
		/* If it's dropped into here it means that this particular character has already been claimed */
		?><h1>Unable to Claim Character</h1>
		
		<p>Sorry, but <?php echo $character->name; ?> has already been claimed and verified as being owned by somebody else. Please try entering another character name and trying again.</p>
		
		<form action="/account/characters/verify" method="post">
			
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

} else {
	
	?><h1>Unable to Find Character</h1>
	
	<p>Sorry, but we could not find a character that name. Please try entering another character name and trying again.</p>
		
	<form action="/account/characters/verify" method="post">
		
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