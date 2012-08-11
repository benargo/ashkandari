<?php // account/password/reset.php

/* 
 * This is the password reset page. Here users can request a reset on their password.
 */
 
// Switch HTTPS on
if( empty($_SERVER['HTTPS']) ) {
	header('Location: https://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(isset($_SESSION['account'])) {
	
	header("Location: ". BASE_URL);
	
}

// Set the page title
$page_title = "Password Reset";

// Require the head of the document
require(PATH.'framework/head.php');

// Check if we have post variables.
if( isset($_POST['email']) ) {

	/* If it's dropped into here it means that we have been provided with $_POST
	 * variables, and as a result we should run the authentication function */
	 
	if($account = new account($_POST['email'], true)) {
		
		/* If it's dropped into here it means that we've been able to create an account based on the given information
		 * So now we can reset the password, and send the activation email */
		
		$account->setPassword();
		$character = $account->getPrimaryCharacter();
		
		/* Generate the email subject */
		$subject = "Complete your Password Reset";
	
		/* Generate the email to send to the new account */
		$message = '<!DOCTYPE html>
		<html>
		<head>
			<title>Activate your Account</title>
			<link type="text/css" rel="stylesheet" href="http://ashkandari.com/css/email.css" />
		</head>
		<body>
			<p>Dear '. $character->name .',</p>
			
			<p>On '. date('jS F Y') .' at '. date('H:i T') .' someone (hopefully you) requested that your password for your account be reset. As a precautionary measure we need to ask you to reactivate your account. You can do this by copy and pasting the link below into your web browser.</p> 
		
			<p><a href="https://ashkandari.com/account/activate/'. $account->id .'/'. $account->activation_code .'">https://ashkandari.com/account/activate/'. $account->id .'/'. $account->activation_code .'</a></p>
		
			<p>Once your account has been reactivated you will be asked to enter a new password, which is required for security reasons.</p>
			
			<p>If you did not request a password reset, then it may indicate that your email account has been compromised and we suggest scan your computer for viruses &amp; spyware, and change your email address password. Either way you will need to reactivate your account.</p>
		
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
		mail($account->email, $subject, $message, $headers);
		
		?><h1>Password Reset</h1>
		
		<p>Thank you <?php echo $character->name; ?>.</p>
		
		<p>We have sent an email to <span class="bold"><?php echo $account->email; ?></span> containing a link to reactivate your account and reset your password. Please follow the instructions contained within that email.</p><?php
		
	} else {
		
		/* We couldn't find their account */
		?><h1>Password Reset</h1>
		
		<p class="error">Sorry, we couldn't find an account with that particular email address.</p>
		
		<p>We're happy to let you try again to reset your password. So if you want to try typing your email address again in the box below, we'll have another look to see if we can find your account.</p>
		
		<form action="/account/password/reset" method="post">
	
			<p><input type="email" name="email" required="true" placeholder="Email Address" /></p>
		
			<p><input type="submit" value="Continue" /></p>
	
		</form><?php
		
	}
	
} else {
	
	/* We need to ask for their email address */
	?><h1>Password Reset</h1>
	
	<p>Hi there,</p>
	
	<p>We're happy to help you reset your password. We need you to type your email address in the box below and we'll have a dig around the old database and see if we can reset your password.</p>
	
	<form action="/account/password/reset" method="post">
	
		<p><input type="email" name="email" required="true" placeholder="Email Address" /></p>
		
		<p><input type="submit" value="Continue" /></p>
	
	</form><?php
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>