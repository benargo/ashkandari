<?php
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the head of the page
require_once('../../framework/config.php');

/* Set the page title */
$page_title = "Forums";

require(PATH.'framework/head.php');

?>

<!-- Forum Title -->
<h1>Guild Forums</h1>

<!-- Breadcrumbs -->
<ul id="breadcrumbs">
	<li><a href="/">Home</a></li>
	<li>Forums</li>
</ul>

<!-- List of boards -->
<table id="boards" class="fill">
	<thead>
		<tr>
			<th>Forum Title</th>
			<th>Topics</th>
			<th>Posts</th>
			<th>Latest Post</th>
		</tr>
	</thead>
	<tbody><?php
	
		/* Get all the forum boards */
		$boards = getAllBoards();
		
		/* Loop through each of the threads */
		while($b = $boards->fetch_object()) {
			
			/* Create a new forum thread object */
			$board = new forum_board($b->id);
			
			/* Check if they're authorised to view this board */
			if($board->isAuthorised($account->id)) {
			
				?><tr>
				
					<td><a href="/forums/<?php echo $board->id; ?>"><?php echo $board->title; ?></a></td>
					<td><?php echo $board->countThreads(); ?></td>
					<td><?php echo $board->countPosts(); ?></td>
					<td><?php echo $board->getLatestUpdate(); ?></td>
					
				</tr>
				
				<tr>
				
					<td colspan="4"><?php echo $board->description; ?></td>
				
				</tr><?php
				
			}
			
		}
	
	?></tbody>
</table><?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>