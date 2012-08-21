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
	
	<?php 
	
	if(isset($_SESSION['account_msg'])) {
		
		?><div id="account_msg" class="<?php 
		
		if(isset($_SESSION['account_msg_class'])) { 
			echo $_SESSION['account_msg_class'];
			unset($_SESSION['account_msg_class']);
		} else { 
			echo 'info';
		} ?>"><?php echo $_SESSION['account_msg']; ?></div><?php
		
		unset($_SESSION['account_msg']);
		
	} ?>
	
	<h2>Email Address</h2>
	<p><?php echo $account->email; ?> [<a href="/account/email/" title="Change your email address and manage your preferences">Edit</a>]</p>
	
	<h2>Password</h2>
	<p>******** [<a href="/account/password/change">Edit</a>]</p>
	
	<h2>Forum Signature</h2>
	<?php if(isset($account->forum_signature)) {
		
		?><section class="signature"><?php echo $account->forum_signature; ?></section>
		<p>[<a href="/account/forums/signature">Edit</a>]</p><?php
		
	} else {
		
		?><p>You don't have a forum signature yet. [<a href="/account/forums/signature">Create one</a>]</p><?php
		
	}
	
	if($account->isModerator()) { ?>
		
	<h2>Forum Moderators</h2>
	<p>As a <span class="moderator">forum moderator</span>, you have special permissions on the <a href="/forums/" title="Click to visit the guild forums">forums</a>, including the ability to:</p>
	<ul>
		<li>Lock threads</li>
		<li>Delete individual posts</li>
		<li>Edit posts that are offensive but overall constructive</li>
	</ul><?php
		
	}
	
	if($account->isOfficer()) { ?>
	
	<h2>Officers Club</h2>
	<p>As an <span class="officer">officer</span> of Ashkandari you can access the management panels for the following sections</p>
	<ul>
		<li><a href="/officers/news/" title="Add, edit or delete news articles">News articles</a></li>
		<li><a href="/officers/epgp/" title="Update the EPGP standings">EPGP</a></li>
		<li><a href="/officers/teams/" title="Add, edit or remove raid teams">Raid Teams</a></li>
		<li><a href="/officers/forums/" title="Forum Control Panel">Forums</a></li>
		<li><a href="/officers/applications/" title="Guild applications">Guild Applications</a></li>
		<li><a href="/officers/accounts/" title="Manage other user accounts">User Accounts</a></li>
	</ul><?php
	
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