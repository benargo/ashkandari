<?php

/* This page is designed to be run as a CRON task, to get all the characters currently
 * in the guild from battle.net, and match them up against our database. */

/* Require the database details */
require_once('../db/mysqli_connection.php')

/* Start creating an instance of the database */
$db = new mysqli($db_details["host"], $db_details["user"], $db_details["password"], $db_details["name"]);

/* Get the contents of the log file */
$file = "/home/ashkandari/logs/characters.cron.log";
$contents = file_get_contents($file);

/* Create a new line in the log which echos the time and date of this CRON job */
$contents .= "CRON Characters => ". date('d M Y') ."\n";

/* Next get the JSON from battle.net */
if( $json = file_get_contents("http://eu.battle.net/api/wow/guild/Tarren-Mill/Ashkandari?fields=members") ) {
	
	/* If it's dropped into here it means that we've been unable to fetch the data from battle.net
	 * So we can now decode the JSON */
	$bnet_data = json_decode($json);
	
	/* Create a new line in the log which echos the fact we successfully retreived
	 * the roster from Battle.net */
	$contents .= "Successfully retreived guild roster from Battle.net\n";
	
	/* The next task is to loop through the each of the characters */
	foreach($bnet_data->members as $member) {
	
		/* Now we can check if each character exists in our database */
		$result = $db->query("SELECT * FROM `characters` WHERE `name` = '". $member->character->name ."' LIMIT 0, 1");
		
		/* Get the object from the query */
		if( $character = $result->fetch_object() ) {
			
			/* If it's dropped into here it means that we've been able 
			 * to find a character with this particular name. In which case
			 * we can run a simple update query to set all the values 
			 * to the up to date versions and set the CRON confirmation
			 * bool to TRUE */
			
			/* And finally update the database */
			if( $db->query("UPDATE `characters` SET class = ". $member->character->class .", race = ". $member->character->race .", gender = ". $member->character->gender .", level = ". $member->character->level .", achievementPoints = ". $member->character->achievementPoints .", rank = ". $member->rank .", thumbnail_url = '". $member->character->thumbnail ."', cron_checked = 1 WHERE `id` = ". $character->id) ) {
				
			/* Create a new line in the log which echos the fact we were able to update the character */
			$contents .= "Updated ". $member->character->name ." (ID ". $character->id .")\n";
				
		} else {
				
			/* Create a new line in the log which echos the fact we were unable to update the character */
			$contents .= "ERROR: Unable to update character ". $member->character->name ." (ID ". $character->id .")\n";	
				
		}
			
		/* And that's it for this section */
			
	} else {
			
		/* If it's dropped into here it means that we've been unable
		 * to find a character with this particular name, which means
		 * that we should create a new record for this character */
		if( $db->query("INSERT INTO `characters` (`name`, `class`, `race`, `gender`, `level`, `achievementPoints`, `rank`, `thumbnail_url`, `cron_checked`) VALUES ('". $member->character->name ."', ". $member->character->class .", ". $member->character->race .", ". $member->character->gender .", ". $member->character->level .", ". $member->character->achievementPoints .", ". $member->rank .", '". $member->character->thumbnail ."', 1)") ) {
				
			/* Create a new line in the log which echos the fact we were able to create a new character */
			$contents .= "Created ". $member->character->name ."\n";
				
		} else {
				
			/* Create a new line in the log which echos the fact we were unable to create a new character */
			$contents .= "ERROR: Unable to create character ". $member->character->name ."\n";
			$contents .= "=> ". $db->error ."\n";
				
		}
			
		/* And that's it for this section */
			
	}
		
		/* Now we can close the result set */
		$result->close();
	
	}
	
	/* Now we need to run one final check to remove any members that
	* are currently in the database but shouldn't be */
	if( $result = $db->query("SELECT `id`, `name` FROM `characters` WHERE `cron_checked` = 0") ) {
		
		/* If it's dropped into here it means that it has found some 
		 * characters where their cron_checked status is false
		 * in which case, they must be purged! */
		 
		/* Loop through each of the characters for the log */
		while( $character = $result->fetch_object() ) {
			
			/* Create a new line in the log reflecting the fact we're about to delete this character */
			$contents .= "Marked ". $character->name ." for purge\n"; 
			
		}
		
		/* Run the query to purge these characters */
		if( $db->query("DELETE FROM `characters` WHERE `cron_checked` = 0") ) {
			
			/* Update the log to reflect this action */
			$contents .= "Purged marked characters (see above)\n";	
			
		} else {
			
			/* Update the log to reflect the fact we were unable to purge the characters */
			$contents .= "ERROR: Unable to purge marked characters\n";
			
		}
		
		/* That's it for this section */
	
	}
	
	/* Now that we're almost done, we just need to reset every character
	* back to a cron_checked status of 0, ready for next time! */
	if( $db->query("UPDATE `characters` SET `cron_checked` = 0") ) {
		
		/* Update the log to reflect the fact we've reset the cron check */
		$contents .= "Set `cron_checked` to 0 for all characters\n";
	
	} else {
		
		/* Update the log to reflect the fact we were unable to reset the cron check */
		$contents .= "ERROR: Unable to reset `cron_checked`\n";
		
	}
	
	/* And now that's it for this section! */		
	
} else {
	
	$contents .= "Unable to fetch roster from Battle.net\n";
	
}

/* Update the log to mark that we're finished */
$contents .= "CRON Character update complete.\n";

$contents .= "Saving log to /home/ashkandari/logs/characters.cron.log\n\n";

file_put_contents($file, $contents);

echo $contents;

?>