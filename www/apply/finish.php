<?php /* apply/index.php */

/* Stage 4
 * This final stage means that we've verified the character, and the player.
 * We can now add all of these details to the database and send it 
 * to the officers for consideration */

// Require the framework
require_once('../../framework/config.php');

// Check if we're already logged in
if(isset($_SESSION['account'])) {
	
		header("Location: https://ashkandari.com/account/");
	
}

// Set the page title
$page_title = "Guild Application";

// Require the page header
require(PATH.'framework/head.php');

/* First, we need to open up a database connection */
$db = db();

/* Secondly, we need to get the values from the form */
$email = decrypt($_POST['email']);
$dob = strtotime($_POST['dob']);
if(is_null($_POST['english'])) { $english = 0; } else { $english = 1; }
if(is_null($_POST['teamspeak'])) { $ts = 0; } else { $ts = 1; }
if(is_null($_POST['microphone'])) { $microphone = 0; } else { $microphone = 1; }
$date = time();
$followup = date('jS F', time()+(3*24*60*60));

/* Thirdly, we need to verify that the code that we passed through the form was correct */
if( $_POST['code'] == decrypt($_POST['code_verify']) ) {

	/* Run the database query to insert this all into the applications database */
	$db->query("INSERT INTO `applications` (`character`, `realm`, `email`, `english`, `teamspeak`, `microphone`, `played_since`, `q1`, `q2`, `q3`, `q4`, `active_spec`, `received_date`) VALUES ( '". $db->real_escape_string($_POST['character']) ."', ". $_POST['realm'] .", '". $db->real_escape_string($email) ."', $english, $ts, $microphone, ". $_POST['played_since'] .", '". $db->real_escape_string($_POST['q1']) ."', '". $db->real_escape_string($_POST['q2']) ."', '". $db->real_escape_string($_POST['q3']) ."', '". $db->real_escape_string($_POST['q4']) ."', ". $_POST['active_spec'] .", $date )") or die($db->error);
	
	/* Get the application ID */
	$app_id = $db->insert_id;
	
	/* Create the new application instance */
	$application = new application($app_id);
	
	/* Get the applicants class */
	$class = $application->getClass();
	
	/* Get the applicants primary spec */
	$spec = $application->getPrimarySpec();
	
	/* Generate the thread title */
	$thread_title = $application->name .": ". $spec->name ." ". $class->name ." - ". $application->getItemLevel() ." item level (". $application->getEquippedItemLevel() ." equipped)";  
	
	/* Run the query to create the forum thread */
	$db->query("INSERT INTO `forum_threads` (`board_id`, `application_id`, `title`, `most_recent_post_time`) VALUES (1, ". $application->id .", '$thread_title', ". time() .")") or die($db->error);
	
	/* Generate the thread ID */
	$thread_id = $db->insert_id;
	
	/* Update the application to include the new thread ID */
	$application->setThread($thread_id);
		
	/* Now we can send out a nice heartwarming message saying well done. */ ?>
	<h1>Application complete!</h1>
	<p>That's it! We're all done here!</p>
	<p>Thank you very much for applying to join us here at Ashkandari. An officer will get back to you very shortly. If you have not heard back by <?php echo $followup; ?> then please contact an officer either in-game or online. A full list of officers and how to contact them can be found in <a href="/officers/">our roster</a>.</p><?php
		
} else {
	
	/* Oh dear, we weren't able to validate the player with the code */
		
	?><h1>Invalid validation code.</h1>
		
	<form action="/apply/finish" method="post">
		
		<input type="hidden" name="realm" value="<?php echo $_POST['realm']; ?>" />
		<input type="hidden" name="character" value="<?php echo $_POST['character']; ?>" />
		<input type="hidden" name="email" value="<?php echo encrypt($email); ?>" />
		<input type="hidden" name="dob" value="<?php echo $_POST['dob']; ?>" />
		<input type="hidden" name="country" value="<?php echo $_POST['country']; ?>" />
		<input type="hidden" name="english" value="<?php echo $_POST['english']; ?>" />
		<input type="hidden" name="teamspeak" value="<?php echo $_POST['teamspeak']; ?>" />
		<input type="hidden" name="microphone" value="<?php echo $_POST['microphone']; ?>" />
		<input type="hidden" name="played_since" value="<?php echo $_POST['played_since']; ?>" />
		<input type="hidden" name="q1" value="<?php echo $_POST['q1']; ?>" />
		<input type="hidden" name="q2" value="<?php echo $_POST['q2']; ?>" />
		<input type="hidden" name="q3" value="<?php echo $_POST['q3']; ?>" />
		<input type="hidden" name="q4" value="<?php echo $_POST['q4']; ?>" />
		<input type="hidden" name="active_spec" value="<?php echo $_POST['active_spec']; ?>" />
		<input type="hidden" name="code_verify" value="<?php echo $_POST['code_verify']; ?>" />
			
		<p>Oh dear, we've fallen over at the last hurdle. It seems that the validation code you entered wasn't right. No worries, we have all of your details still so we can have another go if you want to re-check your email.</p>

		<p><input type="text" name="code" placeholder="XXXXXX" maxlength="6" required="true" /></p>
		<p><input type="submit" value="Finish" /></p>
	
	</form><?php
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>
