<?php
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the head of the page
require_once('../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: https://ashkandari.com/account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

/* Get the board we want */
$board = new forum_board($_GET['id']);

/* Set the page title */
$page_title = $board->title;

require(PATH.'framework/head.php');

/* Check if this board is locked */
if($board->canCreateThread($account->id)) {
	
?><!-- Start new thread button -->

<form action="/forums/new" method="post">

	<input type="hidden" name="board_id" value="<?php echo $board->id;?>" />
	
	<input type="submit" value="New Topic" class="float right" />

</form><?php

} ?>

<!-- Board title -->
<h1><?php echo $board->title; ?></h1>

<!-- Breadcrumbs -->
<ul id="breadcrumbs">
	<li><a href="/">Home</a></li>
	<li><a href="/forums/">Forums</a></li>
	<li><?php echo $board->title; ?></li>
</ul>

<!-- Board description -->
<p><?php echo $board->description; ?></p>

<table class="fill">
	<thead>
		<tr>
			<th>Thread Title</th>
			<th>Started By</th>
			<th>Latest Post</th>
		</tr>
	</thead>
	<tbody><?php
	
		/* Get all the threads */
		$threads = $board->getThreads();
		
		/* Loop through each of the threads */
		while($t = $threads->fetch_object()) {
			
			/* Create a new forum thread object */
			$thread = new forum_thread($t->id);
			$application = $thread->getApplication();
			$character = $thread->getCharacter();
			
			?><tr>
			
				<td><?php
				
				/* Is this topic sticky? */
				if($thread->isSticky()) {
					
					?>[Sticky] <?php
					
				} ?><a href="<?php 
				
				/* Is this topic an application */
				if(isset($application->id)) {
					
					echo "/applications/". $application->id;
					
				} else {
				
					echo "/forums/thread/". $thread->id;
					
				} ?>"><?php echo $thread->title; ?></a><?php
				
				/* Check if the thread is locked */
				if($thread->isLocked()) {
					
					?> <img src="/media/images/icons/locked.gif" alt="Locked" class="noborder" /><?php
					
				}
				
				?></td>
				
				<td><?php if(isset($character->id)) {
					
					?><a href="/roster/character/<?php echo $character->name; ?>" title="Click to see this characters' profile"><?php echo $character->name; ?></a><?php
					
				} elseif(isset($application->id)) {
					
					echo $application->name;
						
				} else {
					
					?>Unknown<?php
					
				} ?></td>
				
				<td><?php echo $thread->getUpdateTime(); ?></td>
				
			</tr><?php		
			
		}
	
	?></tbody>
</table><?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>