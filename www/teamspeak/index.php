<?php // teamspeak.php

/* 
 * This is the website page for the TeamSpeak handler.
 */
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the head of the page
require_once('../../framework/config.php');

$page_title = "TeamSpeak";

require(PATH.'framework/head.php');

/* Create the teamspeak object */
// $teamspeak = new teamspeak();

?><h1>TeamSpeak</h1>

<p>Ashkandari runs a private 512-slot TeamSpeak 3 server. It is open for all members to use and guests are permitted on a temporary basis.</p><?php

/* Check if we are authorised to give out the TeamSpeak connection information */
if(isset($account)) {
	
	/* Set the user */
	//$teamspeak->setUser($account->id);
	
	/* Yes we are */
	?><p>To connect to the server, either <a href="/teamspeak/connect">click here</a> or use the following information:</p>
	
	<table>
		<tbody>
			<tr>
				<td class="bold">Host:</td>
				<td>ashkandari.com</td>
			</tr>
			<tr>
				<td class="bold">Port:</td>
				<td>9987</td>
			</tr>
			<tr>
				<td class="bold">Password:</td>
				<td>(None)</td>
			</tr>
		</tbody>
	</table>
	
	<p>While there is no password required to log in to the TeamSpeak server, all the channels are password protected with the password <span class="bold">hero</span>.</p>
	
	<p>Please do not share the channel password to anyone outside of the guild. If you invite guests onto the teamspeak server, you can move them into your channel by dragging from the home page. To do this you may need to confirm your client as an official guild member. You can do this by connecting through our <a href="/teamspeak/connect">web connection service</a></p>.<?php
	
	/* Check if the user is an officer */
	if($account->isOfficer()) {
	
		/* Yes they are
		 * Check if they're a server admin yet
		if(!$teamspeak->isServerAdmin()) {
			
			// Make them a server admin
			$teamspeak->makeServerAdmin();
			
		} */
		
		?><h2>Officer Privilages</h2>
		<p>As an officer, you are entitled to additional privilages regarding the TeamSpeak server. These include:</p>
		
		<ul>
			<li>Adding, editing and removing channels.</li>
			<li>Banning abusive clients</li>
			<li>Priority speaker status (i.e. when you talk they shut up).</li>
			<li>Access the private officer channels.</li>
		</ul>
		
		<p>To upgrade your client status to officer status, just connect using the <a href="/teamspeak/connect">web connection service</a>, and it will upgrade you automatically.<?php
		
	}
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>