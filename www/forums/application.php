<?php
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://www.ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the head of the page
require_once('../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: /account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

if(empty($_GET['id'])) {
	
	die(header("HTTP/1.1 404 Not Found"));
	
}

/* Get the application we want */
$application = new application($_GET['id']);

/* Set the page title */
$page_title = $application->name ." - Applications";

require(PATH.'framework/head.php');

/* Get the other details */
$thread = $application->getThread();
$realm = $application->getRealm();
$class = $application->getClass();
$race = $application->getRace();
$spec = $application->getPrimarySpec();
$off_spec = $application->getOffSpec();
$profession1 = $application->getProfession(0);
$profession2 = $application->getProfession(1);
$first_aid = $application->getFirstAid();
$fishing = $application->getFishing();
$cooking = $application->getCooking();

?><h1><?php echo $application->name; ?> - Application</h1>

<ul id="breadcrumbs">
	<li><a href="/">Home</a></li>
	<li><a href="/applications/">Applications</a></li>
	<li><?php echo $application->name; ?></li>
</ul>

<?php 

/* Check if this character has had a decision made */
if($application->decided()) {

	/* Get the officer who decided */
	$officer = $application->getOfficer();
	
	/* Yes they have been decided */
	?><p class="info">This character was <?php echo strtolower($application->getDecision()); ?> on <?php echo date('jS F Y', $application->decision_date); ?> by <?php echo $officer->name; ?></p><?php
	
}

/* Print a notice if they're not from our realm */
if($realm->id != 201) {
	
	?><p class="warning">This character is on the realm <span class="bold"><?php echo $realm->name; ?></span>. If accepted, <?php 
	/* Get the right term for the gender */
	switch($application->getGender()) {
		
		case 0:
			echo "he";
			break;
			
		case 1:
			echo "she";
			break;
		
	} ?> will have to transfer to Tarren Mill.</p><?php
	
}

/* Print a notice if they're on Alliance */
if($race->faction == "alliance") {
	
	?><p class="warning">This character is currently <span class="bold">Alliance</span>. If accepted, <?php 
	/* Get the right term for the gender */
	switch($application->getGender()) {
		
		case 0:
			echo "he";
			break;
			
		case 1:
			echo "she";
			break;
		
	} ?> will have to faction change to Horde.</p><?php
	
} ?>

<table class="fill application col4">
	<thead>
		<tr>
			<th colspan="2">Basic Details</th>
			<th colspan="2">Professions</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="bold">Realm:</td>
			<td><?php echo $realm->name; ?></td>
			
			<td class="bold"><img src="<?php echo $profession1->getIcon(); ?>" alt="<?php echo $profession1->name; ?> icon" class="icon" /> <?php echo $profession1->name; ?></td>
			<td><div class="ui-progress-bar ui-container" id="prof1_bar">
				<div class="ui-progress" style="width: <?php echo $profession1->getPercentage(); ?>%;">
					<span class="ui-label"><?php echo $profession1->skill; ?></span>
				</div>
			</div></td>
		</tr>
		<tr>
			<td class="bold">Level:</td>
			<td><?php echo $application->getLevel(); ?></td>
			
			<td class="bold"><img src="<?php echo $profession2->getIcon(); ?>" alt="<?php echo $profession2->name; ?> icon" class="icon" /> <?php echo $profession2->name; ?></td>
			<td><div class="ui-progress-bar ui-container" id="prof2_bar">
				<div class="ui-progress" style="width: <?php echo $profession2->getPercentage(); ?>%;">
					<span class="ui-label"><?php echo $profession2->skill; ?></span>
				</div>
			</div></td>
		</tr>
		<tr>
			<td class="bold">Race:</td>
			<td><img src="<?php echo $application->getRaceIcon(); ?>" alt="<?php echo $race->name; ?> icon" class="icon" /> <?php echo $race->name; ?></td>
			
			<td class="bold"><img src="<?php echo $first_aid->getIcon(); ?>" alt="First Aid icon" class="icon" /> First Aid</td>
			<td><div class="ui-progress-bar ui-container" id="first_aid_bar">
				<div class="ui-progress" style="width: <?php echo $first_aid->getPercentage(); ?>%;">
					<span class="ui-label"><?php echo $first_aid->skill; ?></span>
				</div>
			</div></td>
		</tr>
		<tr>
			<td class="bold">Class:</td>
			<td class="<?php echo $class->slug; ?>"><img src="<?php echo $class->icon_url; ?>" alt="<?php echo $class->name; ?> icon" class="icon" /> <?php echo $class->name; ?></td>
			
			<td class="bold"><img src="<?php echo $fishing->getIcon(); ?>" alt="Fishing icon" class="icon" /> Fishing</td>
			<td><div class="ui-progress-bar ui-container" id="fishing_bar">
				<div class="ui-progress" style="width: <?php echo $fishing->getPercentage(); ?>%;">
					<span class="ui-label"><?php echo $fishing->skill; ?></span>
				</div>
			</div></td>
		</tr>
		<tr>
			<td class="bold">Achievement Points:</td>
			<td><?php echo $application->getAchievementPoints(); ?> <img src="/media/images/icons/achievements.gif" alt="Achievement points icon" class="noborder" /></td>
			
			<td class="bold"><img src="<?php echo $cooking->getIcon(); ?>" alt="Cooking icon" class="icon" /> Cooking</td>
			<td><div class="ui-progress-bar ui-container" id="cooking_bar">
				<div class="ui-progress" style="width: <?php echo $cooking->getPercentage(); ?>%;">
					<span class="ui-label"><?php echo $cooking->skill; ?></span>
				</div>
			</div></td>
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
			<td><?php echo $application->getItemLevel(); ?></td>
		</tr>
		
		<tr>
			<td class="bold">Secondary Spec:</td>
			<td><?php echo $off_spec->name; ?></td>
			
			<td class="bold">Equipped Item Level:</td>
			<td><?php echo $application->getEquippedItemLevel(); ?></td>
		</tr>
	</tbody>
</table>

<h2>Progression</h2>

<?php /* Dragon Soul */
$ds = $application->getProgression(26);

/* Firelands */
$fl = $application->getProgression(25);

?><table class="fill">
	<thead>
		<tr>
			<th colspan="<?php echo $ds->countBosses()+1; ?>">Dragon Soul</th>
		</tr>
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

<h2>Player Details</h2>
<table class="fill">
	<thead>
		<tr>
			<th>Speaks English</th>
			<th>TeamSpeak</th>
			<th>Microphone</th>
			<th>Played Since</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php if($application->english == 1) {
				
				?>Yes<?php
				
			} else {
				
				?>No<?php
				
			} ?></td>
			<td><?php if($application->teamspeak == 1) {
				
				?>Yes<?php
				
			} else {
				
				?>No<?php
				
			} ?></td>
			<td><?php if($application->microphone == 1) {
				
				?>Yes<?php
				
			} else {
				
				?>No<?php
				
			} ?></td>
			<td><?php echo $application->played_since; ?></td>
		</tr>
	</tbody>
</table>

<?php if($account->isOfficer()) {
	
	?><h3>Age &amp; Location.</h3>
	<p><?php echo nl2br($application->q1); ?></p><?php
	
} ?>

<h3>Why <?php
/* Sort the gender out */
switch($application->getGender()) {

	case 0:
		echo "he";
		break;
		
	case 1:
		echo "she";
		break;

} ?> wants to join.</h3>
<p><?php echo nl2br($application->q2); ?></p>

<h3>Background information.</h3>
<p><?php echo nl2br($application->q3); ?></p>

<h3>What makes <?php
/* Sort the gender out */
switch($application->getGender()) {
	
	case 0:
		echo "him";
		break;
		
	case 1:
		echo "her";
		break;
	
} ?> a good player.</h3>
<p><?php echo nl2br($application->q4); ?></p>


<?php if($application->decided() == false && $account->isOfficer()) {
	
	?>
		
		<div class="text center"><form action="https://ashkandari.com/officers/applications/decide.php" method="post" class="decision">
				<input type="hidden" name="id" value="<?php echo $application->id; ?>" />
				<input type="submit" name="decision" value="Accept" />
			</form>
			<form action="https://ashkandari.com/officers/applications/decide.php" method="post" class="decision">
				<input type="hidden" name="id" value="<?php echo $application->id; ?>" />
				<input type="submit" name="decision" value="Decline" />
			</form></div><?php
	
} 

/* Get reply posts */
$posts = $thread->getPosts();

/* Loop through each of the replies */
while($post = $posts->fetch_object()) {
	
	$post = new forum_post($post->id);
	$author = $post->getAccount();
	$character = $post->getCharacter();
	$class = $character->getClass();
	$race = $character->getRace();
	$rank = $character->getRank();
	
	?><section class="reply" id="<?php echo $post->id; ?>"><hr />
		<div class="character">
			<p class="thumb<?php if($character->isModerator()) { echo " moderator"; } if($character->isOfficer()) { echo " officer"; } ?>"><a href="/roster/character/<?php echo $character->name; ?>" class="noborder"><img src="<?php echo $character->getThumbnail(); ?>" alt="Character Thumbnail" /></a></p>
			
			<p style="font-size: 1.2em !important;"><a href="/roster/character/<?php echo $character->name; ?>" class="<?php echo $class->slug; ?>"><?php echo $character->name; ?></a></p>
			
			<p class="<?php echo $class->slug; ?>"><?php echo $character->level; ?> <?php echo $race->name; ?> <?php echo $class->name; ?></p>
			
			<p><a href="/roster/rank/<?php echo $rank->slug; ?>"><?php echo $rank->long_name; ?></a></p>
			
			<?php if($author->isModerator() && !$author->isOfficer()) {
				
				?><p class="moderator">Forum Moderator</p><?php
				
			} ?>
			
			<p><?php echo $character->achievements; ?> <img src="/media/images/icons/achievements.gif" alt="Achievement Points" class="noborder" /></p>

		</div>
		
		<div class="body">
			<?php if($author->id == $account->id || $account->isModerator() || $account->isOfficer()) {
				
				?><div class="float right"><form action="/forums/edit" method="post"><input type="hidden" name="post_id" value="<?php echo $post->id; ?>" />[<input type="submit" value="Edit" class="text" />]</form>
				<form action="/forums/delete" method="post"><input type="hidden" name="post_id" value="<?php echo $post->id; ?>" />[<input type="submit" value="Delete" class="text" />]</form></div><?php
				
			} 
			
			?><div class="body_content<?php if($character->isModerator()) { echo " moderator"; } if($character->isOfficer()) { echo " officer"; } ?>"><?php echo $post->body; ?></div><?php
			
			/* Check if this post has been edited */
			if(isset($post->edit_time)) {
			
				/* Get the editor */
				$editor = $post->getEditor();
				
				/* Get the editors character */
				$editor_character = $editor->getPrimaryCharacter();
				
				/* Print out the updated statement */
				?><p class="italics<?php
				
				/* Check if the editor is a moderator */
				if($editor->isModerator()) {
					
					echo " moderator";
					
				}
				
				/* Check if the editor is an officer */
				if($editor->isOfficer()) {
					
					echo " officer";
					
				} ?>">Edited on <?php echo $post->getEditTime(); ?> by <?php echo $editor_character->name; ?>.</p><?php
				
			}
			
			if(isset($author->forum_signature)) {
				?><div class="signature">
					<?php echo $author->forum_signature; ?>
				</div><?php
			} ?>
		</div>
	</section><?php
	
}

/* Check if the thread is locked */
if($application->decided() == false || $account->isOfficer()) {
	
	$primary_class = $primary_character->getClass();
	$primary_race = $primary_character->getRace();
	$primary_rank = $primary_character->getRank(); 
	
	?><section class="reply"><hr />
		<div class="character">
			<p class="thumb<?php if($primary_character->isModerator()) { echo " moderator"; } if($primary_character->isOfficer()) { echo " officer"; } ?>"><a href="/roster/character/<?php echo $primary_character->name; ?>" class="noborder"><img src="<?php echo $primary_character->getThumbnail(); ?>" alt="Character Thumbnail" /></a></p>
			
			<p style="font-size: 1.2em !important;"><a href="/roster/character/<?php echo $primary_character->name; ?>" class="<?php echo $primary_class->slug; ?>"><?php echo $primary_character->name; ?></a></p>
			
			<p class="<?php echo $primary_class->slug; ?>"><?php echo $primary_character->level; ?> <?php echo $primary_race->name; ?> <?php echo $primary_class->name; ?></p>
			
			<p><a href="/roster/rank/<?php echo $primary_rank->slug; ?>"><?php echo $primary_rank->long_name; ?></a></p>
			
			<?php if($account->isModerator() && !$account->isOfficer()) {
				
				?><p class="moderator">Forum Moderator</p><?php
				
			} ?>
			
			<p><?php echo $primary_character->achievements; ?> <img src="/media/images/icons/achievements.gif" alt="Achievement Points" class="noborder" /></p>
		</div>
		<div class="body">
			<h2>Reply to Thread</h2>
			
			<form action="/forums/reply" method="post">
			
				<input type="hidden" name="thread" value="<?php echo $thread->id; ?>" />
				
				<p><textarea name="body" class="tinymce" rows="10" required="true"></textarea></p>
				<p><input type="submit" value="Post Reply" /></p>
			
			</form>
			<script type="text/javascript"><!--
			(function($) {
				$(document).ready(function() {
					$('textarea').autosize();  
				});
			})(jQuery);
			--></script>
		</div>
	</section><?php
	
} else {

	/* Get the officer who decided */
	$officer = $application->getOfficer();
	
	/* Yes they have been decided */
	?><p class="notice">This character was <?php echo strtolower($application->getDecision()); ?> on <?php echo date('jS F Y', $application->decision_date); ?> by <?php $officer->name; ?>. As a result, comments are no longer allowed.</p><?php
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>