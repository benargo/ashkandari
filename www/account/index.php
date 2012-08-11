<?php // account/index.php

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
$page_title = "My Account &amp; Characters";

// Require the head of the document
require(PATH.'framework/head.php'); 

?><!-- Section 1: Account Details -->
<section id="account-box">
	
	<h1>My Account</h1>
	
	<?php if($account->isOfficer()) {
		
		?><p class="officer text center bold">Guild Officer</p><?php
		
	} elseif($account->isModerator()) {
		
		?><p class="moderator text center bold">Forum Moderator</p><?php
		
	} ?>
	
	<h2>Email Address</h2>
	<p><?php echo $account->email; ?> [<a href="/account/email" title="Change your email address">Edit</a>]</p>
	
	<h2>Password</h2>
	<p>******** [<a href="/account/password/change">Edit</a>]</p>
	
	<h2>Forum Signature</h2>
	<p>[<a href="#" id="forum-signature-show">Show</a>] [<a href="#" id="forum-signature-change">Edit</a>]</p>
	
	<?php if($account->isOfficer()) { ?>
	
	<p><a href="/officers/" class="button" title="Login to the Officer Control Panel">Officers Club</a></p><?php
	
	} ?>
</section>

<section id="characters-box">

	<h1>My Characters</h1>
	
	<?php if(isset($_SESSION['new_primary_character'])) {
		
		$new_primary_character = new character($_SESSION['new_primary_character']);
		
		?><div id="new_primary_character" class="success">Primary character set to <?php echo $new_primary_character->name; ?>.</div><?php
		
		unset($_SESSION['new_primary_character']);
		
	} ?>
	
	<p>Click on a character to set it to your primary character.</p>
	
	<?php
		
		/* Get all characters */
		$characters = $account->getAllCharacters();
		
		/* Loop through the characters */
		while($character = $characters->fetch_object()) {
			
			$character = new character($character->id);
			$race = $character->getRace();
			$class = $character->getClass();
			
			?><a id="<?php echo $character->id; ?>" href="/account/characters/set-primary/<?php echo $character->id; ?>" class="set-primary<?php if($account->primary_character == $character->id) { echo " primary"; } ?>">
				
				<img src="<?php echo $character->getThumbnail(); ?>" alt="Character Thumbnail" class="thumbnail" />
				<h2><?php echo $character->name; ?></h2>
				<p><img src="<?php echo $character->getRaceIcon(); ?>" alt="<?php echo $race->name; ?>" /> <img src="<?php echo $class->icon_url; ?>" alt="<?php echo $class->name; ?>" /> Level <?php echo $character->level; ?> <?php echo $race->name; ?> <?php echo $class->name; ?></p>
			
			</a><?php
		}
	?>
	
	<h2>Add a Character</h2>
	
	<form action="/account/characters/claim" method="post">
		<p>Type your other character's name below:</p>
		<p><select name="character" id="characters">
		<option value=""> </option><?php
		
		/* Get all the characters */
		$characters = character::getAllCharacters();
			
		while( $character = $characters->fetch_object() ) {
				
			?><option value="<?php echo $character->id; ?>"><?php echo $character->name; ?></option><?php
					
		}
			
		?></select></p>
		<p><input type="submit" value="Claim" /></p>
	</form>

</section>


<?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>