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

	<p id="required">= Required</p>

	<label for="realm" class="required"><p>1. What realm is your character currently on? (Start typing your realm's name and pick it from the list that appears)</p>
	<select name="realm" id="realms">
		<option value=""></option><?php
		
		$realms = getAllRealms();
			
		while( $realm = $realms->fetch_object() ) {
				
			?><option value="<?php echo $realm->id; ?>"><?php echo $realm->name; ?></option><?php
					
		}
			
		?></select></label>
		
	<label for="character" class="required"><p>2. What is your character's name? (It needs to be exact, including any accents)</p>
	<input type="text" name="character" maxlength="12" placeholder="Character" required="true" /></label>
	
	<label for="email" class="required"><p>3. What is your email address?</p>
	<input type="email" name="email" placeholder="Email Address" required="true"></label>
	
	<p><small>Your email address is required so that we can contact you with the progress of your application. Your email address is stored in our databases and officers cannot see it directly. For further information please read our <a href="/legal/privacy">privacy policy</a>.</small></p>
	
	<p>4. <input type="checkbox" name="english" /> Do you speak English?</p>
	
	<p>5. <input type="checkbox" name="teamspeak" /> Do you have <a href="/teamspeak/" target="_blank">Teamspeak 3</a> installed?</p>
	
	<p>6. <input type="checkbox" name="microphone" /> Do you have a working microphone?</p>
	
	<label for="played_since" class="required"><p>7. When did you first start playing World of Warcraft?</p>
	<select name="played_since">
		<?php for($year = date('Y'); $year >= 2005 ; $year--) {
			
			?><option value="<?php echo $year; ?>"><?php echo $year; ?></option><?php
			
		} ?>
	</select></label>
	
	<label for="q1" class="required"><p>8. How old are you and where are you from?</p>
	<textarea name="q1" rows="2" required="true"></textarea></label>
	
	<label for="q2" class="required"><p>9. Why do you want to join Ashkandari?</p>
	<textarea name="q2" rows="5" required="true"></textarea></label>
	
	<label for="q3" class="required"><p>10. Tell us a bit about yourself, a bit of your background, what kind of person you are, etc.</p>
	<textarea name="q3" rows="5" required="true"></textarea></label>
	
	<label for="q4" class="required"><p>11. What makes you a good player?</p>
	<textarea name="q4" rows="5" required="true"></textarea></label>
	
	<label for="acceptance" class="required"><p>12. <input type="checkbox" name="acceptance" required="true" /> I agree to follow the <a href="/rules/" target="_blank">guild rules</a> and accept the <a href="/legal/terms" target="_blank">terms &amp; conditions</a> of this website.</p></label>
	
	<p>When you're ready, click Continue. It may take a few moments to fetch and verify your character from Battle.net.</p>
	
	<p><input id="submit" type="submit" value="Continue" /></p>
	
	<script type="text/javascript"><!--
	(function($) {
		$(document).ready(function() {
			$('textarea').autosize();  
		});
	})(jQuery);
	--></script>

</form><?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>