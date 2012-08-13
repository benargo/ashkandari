<?php // account/email/verify.php

/* 
 * This is the login page for the Ashkandari website. It has two versions available to it.
 * One of them being to provide the login form, and the other one being the ability to process
 * the posted login information to us.
 * Which one we use is determined by the presence of $_POST variables.
 */
 
// Switch HTTPS on
if( empty($_SERVER['HTTPS']) ) {
	header('Location: https://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
		header("Location: https://ashkandari.com/account/login?ref=/account/email/");
	
}

// Set the page title
$page_title = "Verify Your New Email Address";

// Require the head of the document
require(PATH.'framework/head.php');

// Get the old and new email addresses
$old_email = $account->email;
$new_email = $_POST['new_email'];

// Run the Change Email Address function
if($account->changeEmail($new_email)) {
	
	/* Prepare the two emails to send */
	
	/* Declare the email headers */
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n";
	$headers .= "From: Ashkandari <account@ashkandari.com>\r\n";
	
	/* Declare the email subject */
	$subject = "Email Address Changed";
	
	/* Generate the email to send to the new account */
	$message = '<!DOCTYPE html>
	<html>
	<head>
		<title>Activate your Account</title>
		<link type="text/css" rel="stylesheet" href="http://ashkandari.com/css/email.css" />
	</head>
	<body>
		<p>Dear '. $primary_character->name .',</p>
		
		<p>On '. date('jS F Y') .' at '. date('H:i T') .' someone (hopefully you) requested that the email address for your account be changed. The new email address is <a href="mailto:'. $new_email .'">'. $new_email .'</a>.</p>
		
		<p>If you <strong>did not</strong> request to change your email address. Then it may indicate that your account has been compromised. This is a very serious nature and we strongly recommend you copy and paste the link below into your web browser to revert to your old email address and change your password.</p>
	
		<p><a href="https://ashkandari.com/account/recover/'. $account->id .'/'. time() .'/'. encrypt($old_email) .'">https://ashkandari.com/account/recover/'. $account->id .'/'. time() .'/'. encrypt($old_email) .'</a></p>
	
		<p>If you <strong>did</strong> request to change your email address, then check the inbox for your new email address to find a link to reactivate your account. This is important to protect your security, and your account will be inactive until such time as you follow the new link.</p>
	
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
	mail($old_email, $subject, $message, $headers);
	
	// Unset the subject and message
	unset($subject, $message);
	
	// Set the new subject
	$subject = "Verify your New Email Address";
	
	$message = '<!DOCTYPE html>
	<html>
	<head>
		<title>Activate your Account</title>
		<link type="text/css" rel="stylesheet" href="http://ashkandari.com/css/email.css" />
	</head>
	<body>
		<p>Dear '. $primary_character->name .',</p>
		
		<p>On '. date('jS F Y') .' at '. date('H:i T') .' we received a request that the email address for this account be changed to <a href="mailto:'. $new_email .'">'. $new_email .'</a>.</p>
		
		<p>As a security precaution, you need to reactivate your account in order to continue using it. You can do this by copy and pasting the link below into your web browser.
	
		<p><a href="https://ashkandari.com/account/activate/'. $account->id .'/'. $account->activation_code .'">https://ashkandari.com/account/activate/'. $account->id .'/'. $account->activation_code .'</a></p>
	
		<p>If you <strong>did not</strong> request to change your email address, then check the inbox of your old email address for instructions to revert to your old email address. Alternatively, you can activate this one and then change it again by going to the following address in your web browser.</p>
		
		<p><a href="https://ashkandari.com/account/email/">https://ashkandari.com/account/email/</a></p>
	
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
	
	/* And send this email too */
	mail($new_email, $subject, $message, $headers);
	
	/* Unset the subject, message body and headers for later usage */
	unset($subject, $message, $headers);
	
	/* As we need them to reactivate their account we should also unset their session */
	unset($_SESSION['account']);
	
	/* Now we can print out a message asking them to check their new email address inbox */
	

?><h1>Verify Your New Email Address</h1>
	
<p>Thanks <?php echo $primary_character->name; ?>. We need you to verify your new email address before we can continue to let you use your account. Please check your inbox for an activation link for you to use. If it's not there, please check your junk mailbox, and add <strong>account@ashkandari.com</strong> to your contacts so you receive emails from us.</p>
<?php

} else {
	
	?><h1>Unable to Change Email Address</h1>
	
	<p>Sorry <?php echo $primary_character->name; ?>, but we were unable to change your email address. You can have another go at changing it if you want below.</p>
	
	<form action="/account/email/verify" method="post">
	
		<p><input type="email" name="email" required="true" /></p>
		<p><input type="submit" value="Change Email Address" /></p>
	
	</form><?php
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>