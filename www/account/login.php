<?php // account/login.php

/* 
 * This is the login page for the Ashkandari website. It has two versions available to it.
 * One of them being to provide the login form, and the other one being the ability to process
 * the posted login information to us.
 * Which one we use is determined by the presence of $_POST variables.
 */
 
// Switch HTTPS on
if( empty($_SERVER['HTTPS']) ) {
	header('Location: https://www.ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../framework/config.php');

// Check if we're already logged in
if(isset($_SESSION['account'])) {
	
	if(isset($_GET['ref'])) {
		
		header("Location: ". $_GET['ref']);
		
	} else {
	
		header("Location: ". BASE_URL);
		
	}	
	
}

// Set the page title
$page_title = "Login";

// Require the head of the document
require(PATH.'framework/head.php');

// Check if we have post variables.
if( isset($_POST['email']) && isset($_POST['password']) ) {

	/* If it's dropped into here it means that we have been provided with $_POST
	 * variables, and as a result we should run the authentication function */
	 
	$result = account::authenticate($_POST['email'], $_POST['password']);
	 
	switch($result) {
	
		/* This switch statement allows us to go through the possible outcomes for the
		 * authenticate function and produce an appropriate output */
		 
		// What to do in the case the account has been suspended
		case "suspended":
		
			?><h1>Account Suspended</h1>
			<p class="error">Sorry, but you account has been suspended and we cannot allow you to log in. You will need to contact an officer to have them review your account.</p><?php
			break;
		
		// What to do in the case the account is inactive
		case "inactive":
		
			?><h1>Account Inactive</h1>
			<p class="error">Sorry, but your account is currently inactive and we cannot allow you to log in until you have activated your account.</p>
			
			<p>You should check your email inbox for your activation link. Alternatively, if you honestly cannot find the activation link, contact an officer to manually activate your account.</p><?php
			break;
	
		// What to do in the case we haven't been able to authenticate the user
		case "fail":
			
			?><h1>Unable to Authenticate</h1>
			<p class="error">Sorry, but either your email address or password was incorrect (for security reasons we can't tell you which one).</p>
			
			<form action="/account/login?ref=<?php echo $_GET['ref']; ?>" method="post">
		
				<p>You can try to login to your account again if you want.</p>
				
				<p>Email:</p>
				<p><input type="email" name="email" required="true" placeholder="Email Address" /></p>
				
				<p>Password:</p>
				<p><input type="password" name="password" required="true" placeholder="Password" /></p>
				
				<p class="text center"><input type="submit" value="Login" /></p>
				
				<p class="text right"><a href="/account/password/reset" title="Reset your password">Forgotten your password?</a></p>
			
			</form>
			
			<?php
			break;
		
		// What to do in the case we have been able to find a user and authenticate them	
		default:
		
			// Set a session variable containing their account ID
			$_SESSION['account'] = $result;
			
			// Check if there is a $_REQUEST['REF']
			if(isset($_GET['ref'])) {
				
				// If it's dropped into here it means there was a referer
				$referal = $_GET['ref'];
				
				// So redirect to that referal
				header("location: $referal");
			
			} else {

				// If it's dropped into here it means that we can just redirect to the home page
				header("location: ". BASE_URL);
				
			}
			
			// Close the end of this switch case
			break;
	}

} else {

	// Obviously we haven't posted any variables so we can print out the login form
	
	?><h1>Login to your Account</h1>
	
	<form action="/account/login?ref=<?php echo $_GET['ref']; ?>" method="post">
		
		<p>Please login to your Ashkandari account using your email address and password.</p>
		
		<p>Email:</p>
		<p><input type="email" name="email" required="true" placeholder="Email Address" /></p>
		
		<p>Password:</p>
		<p><input type="password" name="password" required="true" placeholder="Password" /></p>
		
		<p class="text center"><input type="submit" value="Login" /></p>
		
		<p class="text right"><a href="/account/password/reset" title="Reset your password">Forgotten your password?</a></p>
	
	</form><?php

}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>