<?php # roster/index.php

/* 
 * This is the default page for the guild roster. 
 * If the user is logged in then it will offer a "claim" button which users can use to claim a character
 * as their own through the Battle.net authentication procedure.
 */
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework
require_once('../../framework/config.php');

/* Set the page title */
$page_title = "Guild Roster";

/* Require the head of the page */
require(PATH.'framework/head.php');

?><h1>Guild Roster</h1><?php

/* Set up the database */
$db = db();

if( $roster = $db->query("SELECT `id` FROM `characters` ORDER BY `rank`, `name`") ) {

	/* If it's dropped into here it means that we've succesfully received the guild roster from Battle.net */
	
	?><!-- Roster table -->
	<table id="rosterTable" class="tablesorter fill">
		<thead>
			<tr>
				<th class="sortable">Name</th>
				<th>Race</th>
				<th>Class</th>
				<th class="sortable">Level</th>
				<th class="sortable">Guild Rank</th>
				<th class="sortable">EP</th>
				<th class="sortable">GP</th>
				<th class="sortable">Achievement Points</th>
				<?php if( isset($account) ) {
					?><th>Claimed</th><?php
				} ?>
			</tr>
		</thead>
		<tbody>
	<?php

		/* In here we can loop through each of the members */
		while($member = $roster->fetch_object()) {
		
			$character = new character($member->id);
			$race = $character->getRace();
			$class = $character->getClass();
			$rank = $character->getRank();
			
			/* If it's dropped into here it means we're succesfully looping */
			?><tr>
				<td><a href="/roster/character/<?php echo $character->name; ?>" title="Click to view more details" class="<?php echo $class->slug; ?>"><?php echo $character->name; ?></a></td>
				<td><a href="/roster/race/<?php echo $race->slug; ?>" title="Click to view all the <?php echo $race->name; ?>s" class="noborder"><img src="<?php echo $character->getRaceIcon(); ?>" alt="<?php echo $race->name; ?>" /></a></td>
				<td><a href="/roster/class/<?php echo $class->slug; ?>" title="Click to view all the <?php echo $class->name; ?>s" class="noborder"><img src="<?php echo $class->icon_url; ?>" alt="<?php echo $class->name; ?>" /></a></td>
				<td><?php echo $character->level; ?></td>
				<td class="<?php echo $rank->id; ?>"><a href="/roster/rank/<?php echo $rank->slug; ?>" title="Click to view all the <?php echo $rank->long_name; ?>s"><?php echo $rank->long_name; ?></a></td>
				<td><?php echo $character->ep; ?></td>
				<td><?php echo $character->gp; ?></td>
				<td><?php echo $character->achievements; ?></td>
				<?php if( isset($account) ) {
					
					if($character->isClaimed()) {
						
						?><td>Claimed</td><?php
						
					} else {
						
						?><td><a href="/account/characters/claim/<?php echo $character->id; ?>" title="Claim this character">Claim</a></td><?php
						
					}
					
				} ?>
			</tr>
<?php	} ?>
		</tbody>
	</table>
	
	<script type="text/javascript"><!--
		$(document).ready(function() {
			// add parser through the tablesorter addParser method 
		    $.tablesorter.addParser({ 
		        // set a unique id 
		        id: 'ranks', 
		        is: function(s) { 
		            // return false so this parser is not auto detected 
		            return false; 
		        }, 
		        format: function(s) { 
		            // format your data for normalization 
		            return s.toLowerCase()<?php 
		            
		            	/* Get all the ranks here, and echo them out in the format
		            	 * .replace(/$name/,$id) */
		            	 
		            	if( $ranks = getAllRanks() ) {
			            	
			            	while( $rank = $ranks->fetch_object() ) {
				            	echo '.replace(/'. $rank->long_name .'/,'. $rank->id .')';
			            	}
			            	
		            	}
		            
		            ?>; 
		        }, 
		        // set type, either numeric or text 
		        type: 'numeric' 
		    }); 
		
			$("#rosterTable").tablesorter({
				headers: {
					1: {
						sorter:false
					},
					2: {
						sorter:false
					},
                	4: { 
                    	sorter:'ranks'
                    }<?php if( isset($account) ) {
					?>,
					6: {
						sorter:false
					}<?php } ?>
                } 
			});
		}); 
	--></script>

<?php

	/* Free the result set */
	$roster->close();

} else {
	
	/* If it's dropped into here it means we weren't able to get the guild roster from Battle.net */
	?><p class="error">Unable to fetch guild roster</p>
	<p>Sorry, we've been unable to fetch the guild roster and as a result cannot show you this information. The error has been logged for future investigation. Please try again shortly.</p><?php
	
}

/* Close the database connection */
$db->close();

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>