<?php # roster/character.php

/* 
 * This is the character display page for the guild roster
 */
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework
require_once('../../framework/config.php');

/* Get the character ID */
$character_id = character::getCharacterByName($_GET['name']);

/* Now create a new character object */
if( $character = new character($character_id) ) {
	
	/* Get the other information we may need about this character */
	$class = $character->getClass();
	$race = $character->getRace();
	$gender = $character->getGender();
	$rank = $character->getRank();
	
	/* Set the page title */
	$page_title = $character->name ." - Guild Roster";
	
	/* Require the head of the page */
	require(PATH.'framework/head.php');
	
	?><ul id="breadcrumbs">
		<li><a href="/">Home</a></li>
		<li><a href="/roster/">Guild Roster</a></li>
		<li><a href="/roster/rank/<?php echo $rank->slug; ?>"><?php echo $rank->long_name; ?>s</a></li>
		<li class="<?php echo $class->slug; ?>"><?php echo $character->name; ?></li>
	</ul>
	
	<h1><?php echo $character->name; ?></h1>
	
	<?php if( $character->isFluffy() ) {
		?><p>This character is fluffy =o</p><?php
	} ?>
	
<?php }

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>