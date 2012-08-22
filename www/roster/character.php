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
	$spec = $character->getPrimarySpec();
	$off_spec = $character->getOffSpec();
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
			<td><?php echo $character->getLevel(); ?></td>
			
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
		<tr>
			<td class="bold">Effort Points (EP):</td>
			<td><?php echo $character->ep; ?></td>
			
			<td class="bold">Gear Points (GP):</td>
			<td><?php echo $character->gp; ?></td>
		</tr>
	</tbody>
	<thead>
		<tr>
			<th colspan="2">Specialisations</th>
			<th colspan="2">Item Level</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="bold">Primary Spec:</td>
			<td><?php echo $spec->name; ?></td>
			
			<td class="bold">Average Item Level:</td>
			<td><?php echo $character->getItemLevel(); ?></td>
		</tr>
		
		<tr>
			<td class="bold">Secondary Spec:</td>
			<td><?php echo $off_spec->name; ?></td>
			
			<td class="bold">Equipped Item Level:</td>
			<td><?php echo $character->getEquippedItemLevel(); ?></td>
		</tr>
	</tbody>
</table>

<h2>Progression</h2>

<?php /* Dragon Soul */

?><table class="fill">
	<thead>
		<tr>
			<td></td>
			<?php for($i = 0; $i < $ds->countBosses(); $i++) {
				
				$boss = $ds->getBoss($i);
				
				?><th><?php echo $boss->name; ?></th><?php

			} ?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="bold">Normal Kills:</td>
			<?php for($i = 0; $i < $ds->countBosses(); $i++) {
				
				$boss = $ds->getBoss($i);
				
				?><td><?php echo $boss->normalKills; ?></td><?php

			} ?>
		</tr>
		<tr>
			<td class="bold">Heroic Kills:</td>
			<?php for($i = 0; $i < $ds->countBosses(); $i++) {
				
				$boss = $ds->getBoss($i);
				
				?><td><?php echo $boss->heroicKills; ?></td><?php

			} ?>
		</tr>
	</tbody>
	
	<thead>
		<tr>
			<th colspan="<?php echo $fl->countBosses()+1; ?>">Firelands</th>
		</tr>
		<tr>
			<td></td>
			<?php for($i = 0; $i < $fl->countBosses(); $i++) {
				
				$boss = $fl->getBoss($i);
				
				?><th><?php echo $boss->name; ?></th><?php

			} ?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="bold">Normal Kills:</td>
			<?php for($i = 0; $i < $fl->countBosses(); $i++) {
				
				$boss = $fl->getBoss($i);
				
				?><td><?php echo $boss->normalKills; ?></td><?php

			} ?>
		</tr>
		<tr>
			<td class="bold">Heroic Kills:</td>
			<?php for($i = 0; $i < $fl->countBosses(); $i++) {
				
				$boss = $fl->getBoss($i);
				
				?><td><?php echo $boss->heroicKills; ?></td><?php

			} ?>
		</tr>
	</tbody>
</table>


	
<?php }

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>