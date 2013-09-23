<?php /* apply/index.php */

/*
 * This is the page where guild members can apply to the guild
 * It involves a lot of interaction with battle.net
 */


//
//	
//}

// Require the framework
require_once('../../framework/config.php');

// Check if we're already logged in
if(isset($_SESSION['account'])) {

		header("Location: /account/");

}

// Set the page title
$page_title = "Guild Application";

// Require the page header
require(PATH.'framework/head.php');

?><h1>Apply to join Ashkandari</h1>

<p>We're so thrilled you want to join us here at Ashkandari. In order for us to process your application, we need to learn a little bit about you. Our application form gathers data from Battle.net in order to reduce the work load for you, and for us.</p>

<form id="form1" action="/apply/verify" method="post">

	<p id="required">= Required</p>

	<label for="realm" class="required"><p>What realm is your character currently on? (Start typing your realm's name and pick it from the list that appears)</p>
	<select name="realm" id="realms">
		<option value=""></option><?php

		$realms = getAllRealms();

		while( $realm = $realms->fetch_object() ) {

			?><option value="<?php echo $realm->id; ?>"><?php echo $realm->name; ?></option><?php

		}

		?></select></label>

	<label for="character" class="required"><p>What is your character's name? (It needs to be exact, including any accents)</p>
	<input type="text" name="character" maxlength="12" placeholder="Character" required="true" /></label>

	<label for="email" class="required"><p>What is your email address?</p>
	<input type="email" name="email" placeholder="Email Address" required="true"></label>

	<p><small>Your email address is required so that we can contact you with the progress of your application and will be validated during the next step. Your email address is stored in our databases and officers cannot see it directly. For further information please read our <a href="/legal/privacy">privacy policy</a>.</small></p>

	<p id="q_english"><input type="checkbox" name="english" /> Do you speak English?</p>

	<p id="q_ts3"><input type="checkbox" name="teamspeak" /> Do you have <a href="/teamspeak/" target="_blank">Teamspeak 3</a> installed?</p>

	<p id="q_mic"><input type="checkbox" name="microphone" /> Do you have a working microphone?</p>

	<label id="q_played_since" for="played_since" class="required"><p>When did you first start playing World of Warcraft?</p>
	<select name="played_since">
		<?php for($year = date('Y'); $year >= 2005 ; $year--) {

			?><option value="<?php echo $year; ?>"><?php echo $year; ?></option><?php

		} ?>
	</select></label>

	<label id="q1" for="q1" class="required"><p>How old are you and where are you from?</p>
	<textarea name="q1" rows="2" required="true"></textarea></label>

<!--	<p>Do you intend to raid with us, or simply join us on a social/PvP basis?</p>
	<p><a href="#" class="button" id="btn_raid">I want to raid</a> <a href="#" class="button" id="btn_social">I just want to be social</a></p> -->

	<section>

		<label id="q2" for="q2" class="required"><p>Why do you want to join Ashkandari?</p>
		<textarea name="q2" rows="5" required="true"></textarea></label>

		<label id="q3" for="q3" class="required"><p>Tell us a bit about yourself, a bit of your background, what kind of person you are, etc.</p>
		<textarea name="q3" rows="5" required="true"></textarea></label>

		<label id="q4" for="q4" class="required"><p>What makes you a good player?</p>
		<textarea name="q4" rows="5" required="true"></textarea></label>

	</section>

	<label id="q_acceptance" for="acceptance" class="required"><p><input type="checkbox" name="acceptance" required="true" /> I agree to follow the <a href="/rules/" target="_blank">guild rules</a> and accept the <a href="/legal/terms" target="_blank">terms &amp; conditions</a> of this website.</p></label>

	<p>When you're ready, click Continue. It may take a few moments to fetch and verify your character from Battle.net.</p>

	<p><input id="submit" type="submit" value="Continue" /></p>

	<script type="text/javascript"><!--
	$(document).ready(function() {
		$('textarea').autosize();
	});
	--></script>

</form><?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>