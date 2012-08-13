<?php /* apply/index.php */

/* 
 * This is the page where guild members can apply to the guild
 * It involves a lot of interaction with battle.net
 */
 
// Switch HTTPS on
if( empty($_SERVER['HTTPS']) ) {
	header('Location: https://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

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

?><h1>Apply to join Ashkandari</h1>

<p>We're so thrilled you want to join us here at Ashkandari. In order for us to process your application, we need to learn a little bit about you. Our application form gathers data from Battle.net in order to reduce the work load for you, and for us.</p>

<form id="form1" action="/apply/verify" method="post">

	<p>1. What realm is your character currently on?</p>
	<p><select name="realm"><?php
		
		$realms = getAllRealms();
			
		while( $realm = $realms->fetch_object() ) {
				
			?><option value="<?php echo $realm->id; ?>"<?php if($realm->id == 201) { echo ' selected="true"'; } ?>><?php echo $realm->name; ?></option><?php
					
		}
			
		?></select></p>
		
	<p>2. What is your character's name? (It needs to be exact, including any accents)</p>
	<p><input type="text" name="character" maxlength="12" placeholder="Character" required="true" /></p>
	
	<p>3. What is your email address?</p>
	<p><input type="email" name="email" placeholder="Email Address" required="true"></p>
	
	<p><small>Your email address is required so that we can contact you with the progress of your application. Your email address is stored in our databases and officers cannot see it directly. For further information please read our <a href="/legal/privacy">privacy policy</a>.</small></p>
	
	<p>4. What is your date of birth?</p>
	<p><input type="date" name="dob" required="true" max="<?php echo date("Y-m-d", time()-568080000); ?>" /></p>
	
	<p><small>Ashkandari is a mature guild and we require your date of birth to ensure all members are suitable for our application. It also helps us process data with regards to our <a href="/legal/privacy">privacy policy</a>.</small></p>
	
	<p>5. Which country do you reside in?</p>
	<p><select name="country" id="countries">
		<option value=""> </option><?php
			
			$countries = getAllCountries();
			
			while($country = $countries->fetch_object()) {
				
				?><option value="<?php echo $country->id; ?>"><?php echo utf8_encode($country->name); ?></option><?php
				
			}
			
		?>
	</select></p>
	
	<p>6. <input type="checkbox" name="english" /> Do you speak English?</p>
	
	<p>7. <input type="checkbox" name="teamspeak" /> Do you have <a href="/teamspeak/" target="_blank">Teamspeak 3</a> installed?</p>
	
	<p>8. <input type="checkbox" name="microphone" /> Do you have a working microphone?</p>
	
	<p>9. When did you first start playing World of Warcraft?</p>
	<p><select name="played_since">
		<?php for($year = date('Y'); $year >= 2005 ; $year--) {
			
			?><option value="<?php echo $year; ?>"><?php echo $year; ?></option><?php
			
		} ?>
	</select></p>
	
	<p>10. Why do you want to join Ashkandari?</p>
	<p><textarea name="q1" rows="5" required="true"></textarea></p>
	
	<p>11. Tell us a bit about yourself, a bit of your background, what kind of person you are, etc.</p>
	<p><textarea name="q2" rows="5" required="true"></textarea></p>
	
	<p>12. What makes you a good player?</p>
	<p><textarea name="q3" rows="5" required="true"></textarea></p>
	
	<p>13. <input type="checkbox" name="acceptance" required="true" /> I agree to follow the <a href="/rules/" target="_blank">guild rules</a> and accept the <a href="/legal/terms" target="_blank">terms &amp; conditions</a> of this website.</p>
	
	<p>When you're ready, click Continue. It may take a few moments to fetch and verify your character from Battle.net.</p>
	
	<p><input id="submit" type="submit" value="Continue" /></p>

</form><?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>