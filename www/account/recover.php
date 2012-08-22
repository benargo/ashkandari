<?php // account/recover.php

/* 
 * This page allows us to recover somebodys account based on 3 given variables.
 */
 
// Switch HTTPS on
if( empty($_SERVER['HTTPS']) ) {
	header('Location: https://www.ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../framework/config.php');

// Check if we're already logged in
if(isset($_SESSION['account'])) {
	
		header("Location: /account/");
	
}

// Set the page title
$page_title = "Account Recovery";

// Require the head of the document
require(PATH.'framework/head.php');

// Get the old and new email addresses
$account = new account($_GET['id']);
$character = $account->getPrimaryCharacter();	
$timestamp = $_GET['timestamp'];
$email = decrypt($_GET['old_email']);

// Run the Change Email Address function
if($timestamp > time() - (24*60*60)) {

	/* Change the email address back to what it was */
	$account->changeEmail($email);
	
	/* Prepare the two emails to send */
	
	/* Declare the email headers */
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n";
	$headers .= "From: Ashkandari Account Recovery <recovery@ashkandari.com>\r\n";
	
	/* Declare the email subject */
	$subject = "Account Recovered";
	
	/* Generate the email to send to the new account */
	$message = '<!DOCTYPE html>
	<html>
	<head>
		<title>Activate your Account</title>
		<link type="text/css" rel="stylesheet" href="http://ashkandari.com/css/email.css" />
	</head>
	<body>
		<p>Dear '. $character->name .',</p>
		
		<p>We have been able to recover your account for you. Your email address has been changed back to <a href="mailto:'. $email .'">'. $email .'</a> and we need you to reactivate your account so that you can continue to use our services.</p>
		
		<p>You can do this by copying and pasting the link below into your web browser.</p>
	
		<p><a href="https://ashkandari.com/account/activate/'. $account->id .'/'. $account->activation_code .'">https://ashkandari.com/account/activate/'. $account->id .'/'. $account->activation_code .'</a></p>
	
		<p>Once your account is reactivated we strongly recommend you change your password in order to prevent future account compromises. You can do this by copying and pasting the following link into your web browser.</p>
		
		<p><a href="https://ashkandari.com/account/password/change">https://ashkandari.com/account/password/change</a></p>
	
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
	
	// Send this email
	mail($email, $subject, $message, $headers);
	
	// Unset the subject and message
	unset($subject, $message, $headers);
		
	/* As we need them to reactivate their account we should also unset their session */
	unset($_SESSION['account']);
	
	/* Now we can print out a message asking them to check their new email address inbox */
	

?><h1>Reactivate Your Account</h1>
	
<p>Thanks <?php echo $character->name; ?>. We have been able to recover your account for you. Your email address has been changed back to <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a> and we need you to reactivate your account so that you can continue to use our services. Please check your inbox for an activation link for you to use. If it's not there, please check your junk mailbox, and add <strong>account@ashkandari.com</strong> to your contacts so you receive emails from us.</p>
<?php

} else {
	
	?><h1>Unable to Recover Account</h1>
	
	<p>Sorry <?php echo $primary_character->name; ?>, but we were unable to recover your account. Please contact an officer in-game to have your account recovered manually.</p><?php
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>