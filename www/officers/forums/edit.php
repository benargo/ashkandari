<?php
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Loboardion: http://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Loboardion: https://ashkandari.com/account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

// Set the page title
$page_title = "Add Forum board - Officer Camp";

// Require the head of the document
require(PATH.'framework/head.php'); 

if($account->isOfficer()) {
	
	$board = new forum_board($_GET['id']);
	
	if(isset($board->id)) {
	
		?><h1>Edit Forum board</h1>
		
		<nav class="officer">
			<?php include(PATH.'framework/officer_nav.php'); ?>
			<ul>
				Forums:
				<li><a href="/officers/forums/">Home</a></li>
				<li><a href="/officers/forums/add">Add a Board</a></li>
			</ul>
		</nav>
		
		<form action="/officers/forums/edit-post.php" method="post">
		
			<input type="hidden" name="id" value="<?php echo $board->id; ?>" />
			
			<p>Board Title:</p>
			<p><input type="text" name="title" maxlength="128" required="true" value="<?php echo $board->title; ?>"<?php
			
			/* Check if the board is applications board */
			if($board->id == 1) {
				
				echo ' readonly="true"';
				
			} 
			
			?> /></p>
			
			<p>Board Description:</p>
			<p><textarea name="description" rows="3"><?php echo $board->description; ?></textarea></p>
			
			<p><input type="checkbox" name="officers_only"<?php 
			
			/* Check if this board is for officers only */
			if($board->isOfficerOnly()) {
				
				echo ' checked="true"';
				
			}
			
			/* Check if the board is applications board */
			if($board->id == 1) {
				
				echo ' disabled="true"';
				
			} 
			
			?> /> Should this board be for officers only?</p>
			
			<p><input type="checkbox" name="locked"<?php 
			
			/* Check if the board is locked */
			if($board->isLocked()) {
				
				echo ' checked="true"';
				
			}
			
			/* Check if the board is applications board */
			if($board->id == 1) {
				
				echo ' disabled="true"';
				
			} ?> /> Should this board be locked? (only officers and moderators can create threads)</p>
			
			<p class="text center"><input type="submit" value="Save Board" /></p>
				
		</form>
		
<?php
	} else {
		
		?><h1>Unable to Find board</h1>
		
		<nav class="officer">
			<ul>
				<li><a href="/officers/">Home</a></li>
				<li><a href="/officers/news/">News Articles</a></li>
				<li><a href="/officers/forums/">Forums</a></li>
				<li><a href="/officers/applications/">Guild Appliboardions</a></li>
				<li><a href="/officers/accounts/">User Accounts</a></li>
			</ul>
			<ul>
				Forums:
				<li><a href="/officers/forums/">Home</a></li>
				<li><a href="/officers/forums/add">Add a Board</a></li>
			</ul>
		</nav>
		
		<p>Sorry, but there's no board to edit with the ID number you're searching for. You can choose from a list of available boards to edit from below.</p>
		
		<table class="fill">
			<thead>
				<th>Title</th>
				<th>Edit</th>
				<th>Delete</th>
			</thead>
			<tbody><?php
				
				$boards = getAllBoards();
				
				while($b = $boards->fetch_object()) {
					
					$board = new forum_board($b->id);
							
					?><tr>
						<td><?php echo $board->title; ?></td>
						<td><a href="/officers/forums/edit/<?php echo $board->id; ?>">Edit</a></td>
						<td><a href="/officers/forums/delete/<?php echo $board->id; ?>">Delete</a></td>
					</tr><?php
					
				}
				
			?></tbody>
		</table><?php
		
	}

} else {
	
	header("HTTP/1.1 403 Forbidden");
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>