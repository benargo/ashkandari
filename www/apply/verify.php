<?php /* apply/verify.php */

/* Stage 2
 * This stage of the application process performs an initial lookup of the character,
 * checks it against the guilds application criteria, and then asks them 
 * to validate their character so we can ensure their identity
 */

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

/* First, we need to obtain the variables that were submitted from the previous form */
$realm_id = $_POST['realm']; // Numerical ID
$character_name = $_POST['character']; // Full name
$email_address = encrypt($_POST['email']); // Email Address
	
/* The form returns a numerical ID of the realm. 
 * We need to process that and obtain the full name and the slug. */
$realm = getRealm($realm_id);

/* Now that we know the realm and the character name we can proceed
 * and get the JSON about the character from Battle.net */
if( $json = file_get_contents("http://eu.battle.net/api/wow/character/". $realm->slug ."/$character_name?fields=items,talents,progression") ) {

	/* If we've dropped into this part of the application it means that we've been able to find a character
	 * on this particular realm and of the supplied name. We now need to decode the JSON into a standard class
	 * object so that we can use it within the remainder of our application */
	$character = json_decode($json);
	
	/* Before we continue and display the message to them about verification, we need to validate them against our guild's set criteria.
	 * The criteria is set from the Officer control panel, and is stored in an XML file.
	 * We can pass in the character object we just created to the validator function, which STILL NEEDS TO BE CREATED */
	 if(validate_character($character)) {
		 
		/* Success! The character has passed validation and we can now autheticate the users email address. */
	
		/* Declare the email subject line */
		$subject = "Thank you for applying to Ashkandari";
		
		/* Generate a random code which we will use for authentication purposes
		 * and set it to a session variable */
		$code = strtoupper(substr(md5(time()), 0, 6));
		$_SESSION['validation_code'] = $code;
		
		/* Generate a follow up date of 3 days from now */
		$followup = date('jS F', time() + (3*24*60*60));
		
		/* Define the email message header in full HTML */
		$message = '<!DOCTYPE html>
		<html>
		<head>
			<title>Thank you for applying to Ashkandari</title>
			<link type="text/css" rel="stylesheet" href="http://ashkandari.com/css/email.css" />
		</head>
		<body>
			<p>Dear '. $character->name .',</p>
			<p>Thank you very much for applying to Ashkandari. We are satisfied that you meet our provisional requirements and are happy to continue the application process. In order to finalise your applciation we need you to verify your email address.</p>
		
			<p>The code you need to validate your email address is: <span style="font-weight: bold; font-size: 3em;">'. $code .'</span></p>
			
			<p>Please type this code into the box on our website. Once your email address has been verified your application will be registered into our database, where an officer will get back to you. If you have not heard back by '. $followup .' please either contact an officer in-game or online. A full list of officers and how to contact them can be found at <a href="http://ashkandari.com/officers/">http://ashkandari.com/officers/</a>.</p>
			
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
		
		/* Declare the headers */
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "From: Ashkandari <applications@ashkandari.com>\r\n";
		
		// Mail it
		if( mail($_POST['email'], $subject, $message, $headers) ) {
			
			/* If we've dropped into here it means that we've been able to successfully
			 * send out an email to the user, and we can now continue rendering the page */
			 
			?><h1>Verify your Application</h1>
			
			<form action="/apply/finish" method="post">
				
				<input type="hidden" name="realm" value="<?php echo $realm->id; ?>" />
				<input type="hidden" name="character" value="<?php echo $character->name; ?>" />
				<input type="hidden" name="email" value="<?php echo $email_address; ?>" />
				<input type="hidden" name="english" value="<?php echo $_POST['english']; ?>" />
				<input type="hidden" name="teamspeak" value="<?php echo $_POST['teamspeak']; ?>" />
				<input type="hidden" name="microphone" value="<?php echo $_POST['microphone']; ?>" />
				<input type="hidden" name="played_since" value="<?php echo $_POST['played_since']; ?>" />
				<input type="hidden" name="q1" value="<?php echo $_POST['q1']; ?>" />
				<input type="hidden" name="q2" value="<?php echo $_POST['q2']; ?>" />
				<input type="hidden" name="q3" value="<?php echo $_POST['q3']; ?>" />
				<input type="hidden" name="q4" value="<?php echo $_POST['q4']; ?>" />
				<input type="hidden" name="code_verify" value="<?php echo encrypt($code); ?>" />	
			
				<p><img src="<?php echo $protocol; ?>://eu.battle.net/static-render/eu/<?php echo $character->thumbnail; ?>" alt="Character Thumbnail" id="character_thumbnail" class="float right" />Thank you, <?php echo $character->name; ?>.</p>
				
				<p>Please review the information we've been able to source below about your character. If the character we've been able to find is yours, then please select your active spec then follow the instructions below to verify your email address.</p>
				
				<table id="form2">
					<!-- Realm -->
					<tr>
						<td class="bold">Realm:</td>
						<td><?php echo $character->realm; ?></td>
					</tr>
					
					<!-- Race -->
					<tr>
						<td class="bold">Race:</td>
						<td><?php 
						
							/* This conditional gets the race from the database, based on the ID number given. */
							if( $race = getRace($character->race) ) {
							
								/* We now have to switch through the two possible genders (male and female) provided by battle.net */
								switch ( $character->gender ) {
									
									/* Male */
									case 0:
										?><img src="<?php echo $race->male_icon; ?>" alt="Male Icon" /><?php
									break;
									
									/* Female */
									case 1:
										?><img src="<?php echo $race->female_icon; ?>" alt="Female Icon" /><?php
									break;
									
								} /* End Gender Switch */ ?> <?php 
								
								/* Print out the race's full name */
								echo $race->name;
							
						} else { 
							
							/* This means we've been unable to determine their race */
							echo 'Unknown'; 
						
						} ?></td>
					</tr>
					
					<!-- Class -->
					<tr>
						<td class="bold">Class:</td>
						<td><?php 
						
							/* This condition gets the class from the database, based on the ID number given */
							if( $class = getClass($character->class) ) {
							
							/* Print out the icon for their class */
							?><img src="<?php echo $class->icon_url; ?>" alt="Icon" /> <?php 
							
							/* Print out the class' full name */
							echo $class->name;
							
						} else { 
						
							/* This means we've been unable to determine their class */
							echo 'Unknown'; 
						} ?></td>
					</tr>
					
					<!-- Talents -->
					<tr>
						<td class="bold">Primary Spec:</td>
						<td><select name="active_spec">
							<option value="0"><?php echo $character->talents[0]->name; ?></option><?php
							if( isset($character->talents[1]->name) ) {
							?><option value="1"><?php echo  $character->talents[1]->name; ?></option><?php	
							}
						?></td>
					</tr>
				</table>
				
				<form action="/apply/verify" method="post"><?php
				
				/* Declare the two slots we're going to use for the verification */
				$slot1 = getRandomItemSlot();
				$slot2 = getRandomItemSlot($slot1->name);
				
				?><input type="hidden" name="realm" value="<?php echo $realm->id; ?>" />
				<input type="hidden" name="character" value="<?php echo $character->name; ?>" />
				<input type="hidden" name="english" value="<?php echo $_POST['english']; ?>" />
				<input type="hidden" name="teamspeak" value="<?php echo $_POST['teamspeak']; ?>" />
				<input type="hidden" name="microphone" value="<?php echo $_POST['microphone']; ?>" />
				<input type="hidden" name="played_since" value="<?php echo $_POST['played_since']; ?>" />
				<input type="hidden" name="q1" value="<?php echo $_POST['q1']; ?>" />
				<input type="hidden" name="q2" value="<?php echo $_POST['q2']; ?>" />
				<input type="hidden" name="q3" value="<?php echo $_POST['q3']; ?>" />
				<input type="hidden" name="q4" value="<?php echo $_POST['q4']; ?>" />
				<input type="hidden" name="slot1" value="<?php echo $slot1->id; ?>" />
				<input type="hidden" name="slot2" value="<?php echo $slot2->id; ?>" />
				
				<p>In order to prove that you own the character listed above, we need you to remove the following two pieces of gear from your character and check that you have removed those two pieces. To do this you will need access to your World of Warcraft installation and be able to log in and out.</p>
				
				<p>So, the items we need you to remove are:</p>
		
				<ul>
					<li class="bold"><?php echo $slot1->name; ?></li>
					<li class="bold"><?php echo $slot2->name; ?></li>
				</ul>
			
				<p>Once you have removed those two pieces of gear, we need you to completely log out of your World of Warcraft account (i.e. shut down the game client altogether).</p>			
				<h2>Email Verification</h2>
				
				<p>We have sent an email with a unique code to the email address you supplied just previously. We need you to enter that code below so that we can validate your email address and contact you with the progress of your application. Enter the code in the box below, and then click Finish.</p>
				
				<label for="code"><p><input type="text" name="code" placeholder="XXXXXX" maxlength="6" required="true" /></p></label>
				<p><input id="submit" type="submit" value="Finish" /></p>
				
			</form><?php
			
		} else {
		
			/* If it's dropped into here it means that we weren't able to email the user, 
			 * and we need to ask them to confirm their email address again */
			 
			?><h1>Invalid Email Address</h1>
			
			<p class="error">Unfortunately we weren't able to verify your email address and at the moment we cannot proceed.</p>
			
			<p>Your email address is required so that we can contact you with the progress of your application. Your email address is stored in our databases and officers cannot see it directly. For further information please read our <a href="/legal/privacy">privacy policy</a>.</p>
			
			

				
				<p>We can try again if you want, but first we need you to re-type your email address.</p>
				
				<p id="required">= Required</p>
				
				<label for="email" class="required"><p><input type="email" name="email" placeholder="Email Address" required="true" /></p></label>
				<p><input id="submit" type="submit" value="Continue"></p>
				
			</form><?php
				
		} 
		 
	 } else {
		 
		 /* The character failed the validation */
		 
	 }

} else {
	
	/* If it's dropped into here it means that we weren't able to find a character
	 * of that particular name and realm. In this scenario we should throw them back
	 * and ask them to re-type their characters name and realm. */
	
	?><h1>Unable to verify character</h1>
	
	<form action="/apply/verify" method="post">
		<input type="hidden" name="email" value="<?php echo $email_address; ?>" />
		<input type="hidden" name="english" value="<?php echo $_POST['english']; ?>" />
		<input type="hidden" name="teamspeak" value="<?php echo $_POST['teamspeak']; ?>" />
		<input type="hidden" name="microphone" value="<?php echo $_POST['microphone']; ?>" />
		<input type="hidden" name="played_since" value="<?php echo $_POST['played_since']; ?>" />
		<input type="hidden" name="q1" value="<?php echo $_POST['q1']; ?>" />
		<input type="hidden" name="q2" value="<?php echo $_POST['q2']; ?>" />
		<input type="hidden" name="q3" value="<?php echo $_POST['q3']; ?>" />
		<input type="hidden" name="q4" value="<?php echo $_POST['q4']; ?>" />

		<p class="error">Unfortunately we weren't able to find your character.</p>
		
		<p>Please re-type your character's realm and name, paying close attention to ensuring any accents the name has are included.</p>
		
		<p>What realm is your character currently on?</p>
		<p><select name="realm" id="realms">
		<option value=""> </option><?php
		
		$realms = getAllRealms();
			
		while( $realm = $realms->fetch_object() ) {
				
			?><option value="<?php echo $realm->id; ?>"><?php echo $realm->name; ?></option><?php
					
		}
			
		?></select></p>
		
		<p>What is your character's name?</p>
		<p><input type="text" name="character" maxlength="12" placeholder="Character" required="true" /></p>
		<p><input type="submit" value="Continue" /></p>
		
	</form><?php
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>