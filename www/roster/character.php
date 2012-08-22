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
	$profession1 = $character->getProfession(0);
	$profession2 = $character->getProfession(1);
	$first_aid = $character->getFirstAid();
	$fishing = $character->getFishing();
	$cooking = $character->getCooking();
	
	/* Set the page title */
	$page_title = $character->name ." - Guild Roster";
	
	/* Require the head of the page */
	require(PATH.'framework/head.php');
	
	?><h1><?php echo $character->getCurrentTitle(); ?></h1>
	
	<ul id="breadcrumbs">
		<li><a href="/">Home</a></li>
		<li><a href="/roster/">Guild Roster</a></li>
		<li><a href="/roster/rank/<?php echo $rank->slug; ?>"><?php echo $rank->long_name; ?>s</a></li>
		<li class="<?php echo $class->slug; ?>"><?php echo $character->name; ?></li>
	</ul>
	
	<table class="fill application">
	<thead>
		<tr>
			<th colspan="2">Basic Details</th>
			<th colspan="2">Professions</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="bold">Level:</td>
			<td><?php echo $character->level; ?></td>
			
			<td class="bold"><img src="<?php echo $profession1->getIcon(); ?>" alt="<?php echo $profession1->name; ?> icon" class="icon" /> <?php echo $profession1->name; ?></td>
			<td><div class="ui-progress-bar ui-container" id="prof1_bar">
				<div class="ui-progress" style="width: <?php echo $profession1->getPercentage(); ?>%;">
					<span class="ui-label"><?php echo $profession1->skill; ?></span>
				</div>
			</div></td>
		</tr>
		<tr>
			<td class="bold">Race:</td>
			<td><img src="<?php echo $character->getRaceIcon(); ?>" alt="<?php echo $race->name; ?> icon" class="icon" /> <?php echo $race->name; ?></td>
			
			<td class="bold"><img src="<?php echo $profession2->getIcon(); ?>" alt="<?php echo $profession2->name; ?> icon" class="icon" /> <?php echo $profession2->name; ?></td>
			<td><div class="ui-progress-bar ui-container" id="prof2_bar">
				<div class="ui-progress" style="width: <?php echo $profession2->getPercentage(); ?>%;">
					<span class="ui-label"><?php echo $profession2->skill; ?></span>
				</div>
			</div></td>
		</tr>
		<tr>
			<td class="bold">Class:</td>
			<td class="<?php echo $class->slug; ?>"><img src="<?php echo $class->icon_url; ?>" alt="<?php echo $class->name; ?> icon" class="icon" /> <?php echo $class->name; ?></td>
			
			<td class="bold"><img src="<?php echo $first_aid->getIcon(); ?>" alt="First Aid icon" class="icon" /> First Aid</td>
			<td><div class="ui-progress-bar ui-container" id="first_aid_bar">
				<div class="ui-progress" style="width: <?php echo $first_aid->getPercentage(); ?>%;">
					<span class="ui-label"><?php echo $first_aid->skill; ?></span>
				</div>
			</div></td>
		</tr>
		<tr>
			<td class="bold">Guild Rank:</td>
			<td><?php echo $rank->long_name; ?></td>
			
			<td class="bold"><img src="<?php echo $fishing->getIcon(); ?>" alt="Fishing icon" class="icon" /> Fishing</td>
			<td><div class="ui-progress-bar ui-container" id="fishing_bar">
				<div class="ui-progress" style="width: <?php echo $fishing->getPercentage(); ?>%;">
					<span class="ui-label"><?php echo $fishing->skill; ?></span>
				</div>
			</div></td>
		</tr>
		<tr>
			<td class="bold">Achievement Points:</td>
			<td><?php echo $character->achievements; ?> <img src="/media/images/icons/achievements.gif" alt="Achievement points icon" class="noborder" /></td>
			
			<td class="bold"><img src="<?php echo $cooking->getIcon(); ?>" alt="Cooking icon" class="icon" /> Cooking</td>
			<td><div class="ui-progress-bar ui-container" id="cooking_bar">
				<div class="ui-progress" style="width: <?php echo $cooking->getPercentage(); ?>%;">
					<span class="ui-label"><?php echo $cooking->skill; ?></span>
				</div>
			</div></td>
		</tr>
	</tbody>
</table>
<table class="fill">
	<thead>
		<tr>
			<th colspan="2">Specialisations</th>
			<th colspan="2">Item Level</th>
			<th colspan="2">EPGP</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="bold">Primary Spec:</td>
			<td><?php echo $character->getPrimarySpec(); ?></td>
			
			<td class="bold">Average Item Level:</td>
			<td><?php echo $character->getItemLevel(); ?></td>
			
			<td class="bold">Effort Points (EP):</td>
			<td><?php echo $character->ep; ?></td>
		</tr>
		
		<tr>
			<td class="bold">Secondary Spec:</td>
			<td><?php echo $character->getOffSpec(); ?></td>
			
			<td class="bold">Equipped Item Level:</td>
			<td><?php echo $character->getEquippedItemLevel(); ?></td>
			
			<td class="bold">Gear Points (GP):</td>
			<td><?php echo $character->gp; ?></td>
		</tr>
	</tbody>
</table>
	<?php if($character->isClaimed()) {
		
		?><h2>Alts</h2><?php
		
		$alts = $character->getAlts();
		
		while($obj = $alts->fetch_object()) {
		
			$alt = new character($obj->id);
		
			$class = $alt->getClass();
			$race = $alt->getRace();
			
			?><a href="/roster/character/<?php echo $alt->name; ?>" class="display block clear both">
				<img src="<?php echo $alt->getThumbnail(); ?>" alt="Character Thumbnail" class="float right" />
				<p class="bold"><?php echo $alt->name; ?></p>
				<p class="<?php echo $class->slug; ?>"><img src="<?php echo $character->getRaceIcon(); ?>" alt="Race Icon" /> <img src="<?php echo $class->icon_url; ?>" alt="Class Icon" /> Level <?php echo $alt->level; ?> <?php echo $race->name; ?> <?php echo $class->name; ?></p>
				<p>Achievement Points: <?php echo $alt->achievements; ?> <img src="/media/images/icons/achievements.gif" alt="Achievement points icon" class="noborder" /></p>
			</a><?php
			
		}
		
	}

}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>