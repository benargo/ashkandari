<?php /* account/register/activate.php */

/* 
 * In this final stage, we send out an email with an activation code for them to activate their account.
 */

// Require the framework
require_once('../../../framework/config.php');

// Check if we're already logged in
if(isset($_SESSION['account'])) {
	
		header("Location: https://ashkandari.com/account/");
	
}

// Set the page title
$page_title = "Verify your Character";

// Require the page header
require(PATH.'framework/head.php');

/* Now we can get the character from the database */
$character = new character($_POST['character']);

/* Run the verification on the character */
if( $character->verify($_POST['slot1'], $_POST['slot2']) ) {
	
	/* If it's dropped into here it means that we've successfully verified the character
	 * So the next step is to add the account to the database.
	 * But before we can do that we need to generate an activation code */
	$code = md5(time());
	
	/* Open a database connection */
	$db = db();
	
	/* Run the database query to insert the account */ 
	$db->query("INSERT INTO `accounts` (`email`, `password`, `activation_code`, `primary_character`) VALUES ('". $_POST['email'] ."', '". $_POST['password'] ."', '$code', ". $character->id .")");
	
	/* Get the account ID from the previous query */
	$account_id = $db->insert_id;
	
	/* Change the value in the characters table to reflect this new claim of ownership */
	$db->query("UPDATE `characters` SET `account_id` = $account_id WHERE `id` = ". $character->id);
	
	/* Can close the database connection */
	$db->close();
	
	/* Generate the email subject */
	$subject = "Activate your Ashkandari Account";
	
	/* Generate the email to send to the new account */
	$message = '<!DOCTYPE html>
	<html>
	<head>
		<title>Activate your Account</title>
		<link type="text/css" rel="stylesheet" href="http://ashkandari.com/css/email.css" />
	</head>
	<body>
		<p>Dear '. $character->name .',</p>
		
		<p>Thank you very much for creating an account with Ashkandari. To activate your account we need you to copy and paste the link below:</p>
	
		<p><a href="https://ashkandari.com/account/activate/'. $account_id .'/'. $code .'">https://ashkandari.com/account/activate/'. $account_id .'/'. $code .'</a></p>
	
		<p>Once your email address has been verified your account will be fully activated and you will be able to use the full features of our website.</p>
	
		<p>For the Horde!</p>
		<p style="text-style: italics; font-size: 1.5em;">Ashkandari</p>
	
		<hr />
	
		<footer style="color: #333333;">
			<p><span style="font-weight: bold;">Privacy Notice:</span> The information contained within this email is both private and confidential. If you are not the intended recipient, please delete this email from your system. Ashkandari respects your privacy and will never email you without your concent, nor will we pass on your details to any third party person or organisation under any circumstances. For further information, please visit <a href="http://ashkandari.com/legal/privacy">http://ashkandari.com/legal/privacy</a>. Thank you for your support and cooportation.</p>
			<p><span style="font-weight: bold;">Disclaimer:</span> World of Warcraft&trade;, Mists of Pandaria&trade; and Blizzard Entertainment&trade; are all trademarks or registered trademarks of Blizzard Entertainment Inc. internationally. All related materials, logos, and images are copyright &copy; Blizzard Entertainment Inc. Ashkandari is in no way associated with or endorsed by Blizzard Entertainment.</p>
			<p>Copyright &copy; '. date('Y') .' Ashkandari</p>
		</footer>
	</body>
	</html>';
	
	/* Declare the email headers */
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n";
	$headers .= "From: Ashkandari <account@ashkandari.com>\r\n";
	
	// Mail it
	if( mail($_POST['email'], $subject, $message, $headers) ) {
		
		/* Now we can print out a success message */
		?><h1>Activate your Account</h1>
		
		<p>Hey <?php echo $character->name; ?>!</p>
		
		<p>Thanks for doing that. I know it's a bit of a hastle but we need to be certain that you really are who you say you are.</p>
		
		<p>We're pretty much done here, but there's just one final hurdle we need to clear. We've sent an email to <span class="italics"><?php echo $_POST['email']; ?></span> with a link you'll need to copy and paste into your browser in order to fully activate your account. You might want to double-check your junk folder if it's not there, and add <span class="italics">account@ashkandari.com</span> to your contacts or your allow list for future reference.</p><?php
		
	} else {
		
		?><h1>Activation Failed</h1>
		
		<p>We had a small problem with our activation procedures, so unfortuantely we're not able to fully activate your account. If you speak to an officer in-game they should be able to verify you manually.</p><?php
		
	}
	
} else {
	
	/* If it's dropped into here it means that this particular character has already been claimed */
	?><h1>Unable to Claim Character</h1>
	
	<p>Sorry, but <?php echo $character->name; ?> has already been claimed and verified as being owned by somebody else. Please try entering another character name and trying again.</p>
	
	<form action="/account/register/verify" method="post">
	
		<input type="hidden" name="email" value="<?php echo $_POST['email']; ?>" />
		<input type="hidden" name="password" value="<?php echo $_POST['password']; ?>" />
		
		<p>Please retype your character name, including any accents. The system should recognise your characters name and just select it from the list that forms.</p>
		<p><select name="character" id="characters">
			<option value=""> </option><?php
			
			/* Get all the characters */
			$characters = character::getAllCharacters();
				
			while( $char = $characters->fetch_object() ) {
					
				?><option value="<?php echo $char->id; ?>"><?php echo $char->name; ?></option><?php
						
			}
				
			?></select></p>
			
		<p>If your character is not showing up correctly, it may mean the guild roster is slightly out of date. The roster is automatically updated every morning at 23:00 UTC so please try again after that.</p>
	
		<p>When you're ready, click Continue. It may take a few minutes to fetch and verify your character from Battle.net, so please be patient and do not refresh the page.</p>
		<p><input id="submit" type="submit" value="Continue" /></p>
	
	</form><?php
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>