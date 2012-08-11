<?php # roster/character.php

/* 
 * This is the character display page for the guild roster
 */
 
/* Switch HTTPS off */
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

/* Require the framework */
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
	
	?><section class="float right">
		<h2 class="text center">Profile</h2>
		<p class="text center<?php if($character->isModerator()) { echo " moderator"; } if($character->isOfficer()) { echo " officer"; } ?>"><img src="<?php echo $character->getThumbnail(); ?>" alt="Character Thumbnail" /></p>
		<?php if($character->isModerator()) { ?><p class="moderator text center bold">Forum Moderator</p><?php } ?>
		<table>
			<tr>
				<td class="bold">Guild Rank:</td>
				<td colspan="2"><a href="/roster/rank/<?php echo $rank->slug; ?>"><?php echo $rank->long_name; ?></a></td>
			</tr>
			<tr>
				<td class="bold">Level:</td>
				<td colspan="2"><?php echo $character->level; ?></td>
			</tr>
			<tr>
				<td class="bold">Race:</td>
				<td><img src="<?php echo $character->getRaceIcon(); ?>" alt="<?php echo $race->name; ?>" /></td>
				<td><?php echo $race->name; ?></td>
			</tr>
			<tr>
				<td class="bold">Class:</td>
				<td><img src="<?php echo $class->icon_url; ?>" alt="<?php echo $class->name; ?>" /></td>
				<td class="<?php echo $class->slug; ?>"><?php echo $class->name; ?></td>
			</tr>
			<tr>
				<td class="bold">Specialisations:</td>
				<td class="bold"><?php echo $character->getActiveSpec(); ?></td>
				<td><?php echo $character->getOffSpec(); ?></td>
			</tr>
			<tr>
				<td class="bold">Achievement Points:</td>
				<td colspan="2"><?php echo $character->achievements; ?> <img src="/media/images/icons/achievements.gif" alt="points" class="noborder" /></td>
			</tr>
			<tr>
				<td class="bold">Average Item Level:</td>
				<td colspan="2"><?php echo $character->getAverageItemLevel(); ?> (<?php echo $character->getAverageItemLevel(true); ?> equipped)</td>
			</tr>
		</table>
		
	</section>

	
	<ul id="breadcrumbs">
		<li><a href="/">Home</a></li>
		<li><a href="/roster/">Guild Roster</a></li>
		<li><a href="/roster/rank/<?php echo $rank->slug; ?>"><?php echo $rank->long_name; ?>s</a></li>
		<li class="<?php echo $class->slug; ?>"><?php echo $character->name; ?></li>
	</ul>
	
	<section>
		<h1><?php echo $character->getCurrentTitle(); ?></h1>
		<p class="<?php echo $class->slug; ?>">Level <?php echo $character->level; ?> <?php echo $race->name; ?> <?php echo $character->getActiveSpec(); ?> <?php echo $class->name; ?></p>
		<p>The rest of this section will fill up later, I promise. It's just I need to focus on other stuff and implement this once the forums are done.</p>
	</section>	
	
<?php }

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>