<?php // officers/applications/index.php

/* 
 * This is the page for Ashkandari's officer section where officers can review outstanding applications.
 * It also includes links for officers to review previously accepted or declined applications.
 */
 
// Switch HTTPS on
if( empty($_SERVER['HTTPS']) ) {
	header('Location: https://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

// Set the page title
$page_title = "Guild Applications - Officer Camp";

// Require the head of the document
require(PATH.'framework/head.php'); 

if($account->isOfficer()) {
	
	?><h1>Guild Applications</h1>

<nav class="officer">
	<ul>
		<li><a href="/officers/">Home</a></li>
		<li><a href="/officers/news/">News Articles</a></li>
		<li><a href="/officers/forums/">Forums</a></li>
		<li><a href="/officers/applications/">Guild Applications</a></li>
		<li><a href="/officers/accounts/">User Accounts</a></li>
	</ul>
	<ul>
		Applications:
		<li><a href="/officers/applications/" class="bold">All</a></li>
		<li><a href="/officers/applications/undecided">Awaiting Decision</a></li>
		<li><a href="/officers/applications/accepted">Accepted</a></li>
		<li><a href="/officers/applications/declined">Declined</a></li>
	</ul>
</nav>

<table class="fill">
	<thead>
		<tr>
			<th>Character Name</th>
			<th>Level</th>
			<th>Race</th>
			<th>Class</th>
			<th>Spec</th>
			<th>Received Date</th>
			<th>Decision</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		/* Get the outstanding applications */
		$applications = getApplications();
		
		/* Loop through each of the applications */
		while($app = $applications->fetch_object()) {
		
			/* Get the realm information */
			$realm = getRealm($app->realm);
		
			/* Get the character from Battle.net */
			$json = file_get_contents("http://eu.battle.net/api/wow/character/". $realm->slug ."/". strtolower($app->character) ."?fields=talents");
			$character = json_decode($json);
			
			/* Get the class */
			$race = getRace($character->race);
			$class = getClass($character->class);
			
			$spec1 = $character->talents[0];
			$spec2 = $character->talents[1];
		
			/* Print out the table */	
			?><tr>
				<td><a href="/applications/<?php echo $app->id; ?>" title="View this application"><?php echo $app->character; ?></a></td>
				<td>Level <?php echo $character->level; ?></td>
				<td><?php echo $race->name; ?></td>
				<td><?php echo $class->name; ?></td>
				<td><?php echo $spec1->name; ?> / <?php echo $spec2->name; ?>
				<td><?php echo date('j F Y', $app->received_date); ?></td>
				<td><?php switch($app->decision) {
					
					case NULL:
						echo "Awaiting Decision";
						break;
						
					case 0:
						echo "Declined";
						break;
						
					case 1:
						echo "Accepted";
						break;
					
				} ?>
			</tr>
			<?php
			
		} ?>
	</tbody>
</table>

<?php

} else {
	
	header("HTTP/1.1 403 Forbidden");
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>