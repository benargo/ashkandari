<?php // /applications/index.php

/* 
 * This is the page for Ashkandari's website where players can review outstanding applications.
 * It also includes links for officers to review previously accepted or declined applications.
 */
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://www.ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: /account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

// Set the page title
$page_title = "Guild Applications";

// Require the head of the document
require(PATH.'framework/head.php'); 

?><h1>Guild Applications</h1>

<nav>
	<ul>
		<li><a href="/applications/">All</a></li>
		<li><a href="/applications/undecided">Awaiting Decision</a></li>
		<li><a href="/applications/accepted">Accepted</a></li>
		<li><a href="/applications/declined">Declined</a></li>
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
			<th>Off Spec</th>
			<th>Received Date</th>
			<th>Decision</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		
		/* Work out what application we're getting */
		if(isset($_GET['decision'])) {	
		
			/* Get the applications */
			$applications = getApplications($_GET['decision']);
		
		} else {			
				
			/* Get the outstanding applications */
			$applications = getApplications();
						
		}
		
		/* Loop through each of the applications */
		while($application = $applications->fetch_object()) {
		
			$app = new application($application->id);
			
			/* Get the class */
			$race = $app->getRace();
			$class = $app->getClass();
			
			$spec = $app->getPrimarySpec();
			$off_spec = $app->getOffSpec();
		
			/* Print out the table */	
			?><tr>
				<td><a href="/applications/<?php echo $app->id; ?>" title="View this application"><?php echo $app->name; ?></a></td>
				<td><?php echo $app->getLevel(); ?></td>
				<td><?php echo $race->name; ?></td>
				<td><?php echo $class->name; ?></td>
				<td><?php echo $spec->name; ?></td>
				<td><?php echo $off_spec->name; ?></td>
				<td><?php echo date('j F Y', $app->received_date); ?></td>
				<td><?php echo $app->getDecision(); ?></td>
			</tr>
			<?php
			
		} ?>
	</tbody>
</table>

<?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>