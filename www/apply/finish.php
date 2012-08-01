<?php /* apply/index.php */

/* Stage 4
 * This final stage means that we've verified the character, and the player.
 * We can now add all of these details to the database and send it 
 * to the officers for consideration */

// Require the framework
require_once('../../framework/config.php');

// Set the page title
$page_title = "Guild Application";

// Require the page header
require(PATH.'framework/head.php');

/* First, we need to open up a database connection */
$db = config::db();

/* Secondly, we need to get the values from the form */
$character = $_POST['character'];
$realm = $_POST['realm'];
$email = $_POST['email'];
$date = time();
$followup = date('jS F', time()+(3*24*60*60));

/* Thirdly, we need to verify that the code that we passed through the form was correct */
if( $_POST['code'] == $_SESSION['validation_code'] ) {

	/* Run the database query to insert this all into the database */
	$db->query("INSERT INTO `applications` (`character`, `realm`, `email`, `received_date`) VALUES ( '$character', $realm, '$email', $date )");
		
	/* Now we can send out a nice heartwarming message saying well done. */ ?>
	<h1>Application complete!</h1>
	<p>That's it! We're all done here!</p>
	<p>Thank you very much for applying to join us here at Ashkandari. An officer will get back to you very shortly. If you have not heard back by <?php echo $followup; ?> then please contact an officer either in-game or online. A full list of officers and how to contact them can be found in <a href="/roster/rank/officer">our roster</a>.</p><?php
		
} else {
	
	/* Oh dear, we weren't able to validate the player with the code */
		
	?><h1>Invalid validation code.</h1>
		
	<form action="/apply/finish" method="post">
		
		<input type="hidden" name="realm" value="<?php echo $realm; ?>" />
		<input type="hidden" name="character" value="<?php echo $character; ?>" />
		<input type="hidden" name="email" value="<?php echo $email; ?>" />
			
		<p>Oh dear, we've fallen over at the last hurdle. It seems that the validation code you entered wasn't right. No worries, we have all of your details still so we can have another go if you want to re-check your email.</p>

		<p><input type="text" name="code" placeholder="XXXXXX" maxlength="6" required="true" /></p>
		<p><input type="submit" value="Finish" /></p>
	
	</form><?php
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>
