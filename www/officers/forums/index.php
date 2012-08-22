<?php
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://www.ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: /account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

// Set the page title
$page_title = "Forums - Officer Camp";

// Require the head of the document
require(PATH.'framework/head.php'); 

if($account->isOfficer()) {
	
	?><h1>Forums</h1>
	
	<?php if(isset($_SESSION['msg']) && isset($_SESSION['msg_status'])) {
		
		?><p class="<?php echo $_SESSION['msg_status']; ?>"><?php echo $_SESSION['msg']; ?></p><?php
		
		unset($_SESSION['msg'], $_SESSION['msg_status']);
		
	} ?>

<nav class="officer">
	<?php include(PATH.'framework/officer_nav.php'); ?>
</nav>

<form action="/officers/forums/update-order.php" method="post">
	<table class="fill">
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Order</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody><?php
			
			/* Get the all the boards */
			$boards = getAllBoards();
	
			while($b = $boards->fetch_object()) {
				
				$board = new forum_board($b->id);
				
				?><tr>
					<td><?php echo $board->id; ?></td>
					<td><?php echo $board->title; ?></td>
					<td><select name="order_<?php echo $board->id; ?>">
						<?php for($i = 1; $i <= $boards->num_rows; $i++) {
							/* Create an option for each row */
							?><option value="<?php echo $i; ?>"<?php 
							
							/* Calculate if this is the one we want to mark as selected */
							if($i == $board->order) {
								
								echo ' selected="true"';
							
							} ?>><?php echo $i; ?></option><?php
						} 
					?></select></td>
					<td><a href="/officers/forums/edit/<?php echo $board->id; ?>">Edit</a></td>
					<td><?php if($board->id != 1) { ?><a href="/officers/forums/delete/<?php echo $board->id; ?>"><?php } ?>Delete<?php if($board->id != 1) { ?></a><?php } ?></td>
				</tr><?php
			} ?>
		</tbody>
	</table>
	<p class="text center"><a href="/officers/forums/add" class="button">Add a Board</a> <input type="submit" value="Update Order" /></p>
</form>

<?php

} else {
	
	header("HTTP/1.1 403 Forbidden");
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>