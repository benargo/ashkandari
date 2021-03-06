<?php
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://www.ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the head of the page
require_once('../../../framework/config.php');

/* Get the new forum thread */
$thread = new forum_thread($_GET['id']);

/* Get the thread's board */
$board = $thread->getBoard();

/* Check if it has an application */
if($thread->isApplication()) {

	/* Get the application (if there is one) */
	$application = $thread->getApplication();
	
	header("Location: /applications/". $application->id);
	
}

/* Set the page title */
$page_title = $thread->title ." - ". $board->title;

/* Get the header */
require(PATH.'framework/head.php');

/* Check if we should show the moderation functions */
if(isset($account)) {

	if($account->isModerator() || $account->isOfficer()) {
	
	?><div class="float right">
	
		<form action="/forums/lock" method="post">
			<input type="hidden" name="thread_id" value="<?php echo $thread->id; ?>" />
			[<input type="submit" name="action" value="<?php
			
			/* Check if we're switching to "lock" or "unlock" */
			if($thread->isLocked()) {
				
				echo "Unlock";				
				
			} else {
				
				echo "Lock";
				
			}
			
			?>" class="text" />]
		</form>
		
		<form action="/forums/sticky" method="post">
			<input type="hidden" name="thread_id" value="<?php echo $thread->id; ?>" />
			[<input type="submit" name="action" value="<?php
			
			/* Check if we're switching to "Make Sticky" or "Remove Sticky" */
			if($thread->isSticky()) {
			
				echo "Remove Sticky";
			
			} else {
			
				echo "Make Sticky";
			
			} ?>" class="text" />]
		</form>
	
	</div><?php
	
	}
	
}

?><h1><?php

/* Check if this topic is sticky AND locked */
if($thread->isSticky() && $thread->isLocked()) {
	
	echo "[Sticky Locked] ";
	
} elseif($thread->isSticky()) {
	
	/* Thread is sticky but not locked */
	echo "[Sticky] ";
	
} elseif($thread->isLocked()) {
	
	/* Thread is locked but not sticky */
	echo "[Locked] ";
	
}

/* Echo thread title */
echo $thread->title; ?></h1>

<ul id="breadcrumbs">
	<li><a href="/">Home</a></li>
	<li><a href="/forums/">Forums</a></li>
	<li><a href="/forums/<?php echo $board->id; ?>"><?php echo $board->title; ?></a></li>
	<li><?php echo $thread->title; ?></li>
</ul><?php

/* Get the posts */
$posts = $thread->getPosts();

/* Loop through the posts */
while($p = $posts->fetch_object()) {
	
	$post = new forum_post($p->id);
	$author = $post->getAccount();
	$character = $post->getCharacter();
	$class = $character->getClass();
	$race = $character->getRace();
	$rank = $character->getRank();
	
	?><section class="reply" id="<?php echo $post->id; ?>"><hr />
		<div class="character">
			<p class="thumb<?php if($author->isModerator()) echo " moderator"; if($author->isOfficer()) echo " officer"; ?>"><a href="/roster/character/<?php echo $character->name; ?>" class="noborder"><img src="<?php echo $character->getThumbnail(); ?>" alt="Character Thumbnail" /></a></p>
			
			<p style="font-size: 1.2em !important;"><a href="/roster/character/<?php echo $character->name; ?>" class="<?php echo $class->slug; ?>"><?php echo $character->name; ?></a></p>
			
			<p class="<?php echo $class->slug; ?>"><?php echo $character->level; ?> <?php echo $race->name; ?> <?php echo $class->name; ?></p>
			
			<p><a href="/roster/rank/<?php echo $rank->slug; ?>"><?php echo $rank->long_name; ?></a></p>
			
			<?php if($author->isModerator() && !$author->isOfficer()) {
				
				?><p class="moderator">Forum Moderator</p><?php
				
			} ?>
			
			<p><?php echo $character->achievements; ?> <img src="/media/images/icons/achievements.gif" alt="Achievement Points" class="noborder" /></p>

		</div>
		
		<div class="body">
			<?php if(isset($account)) {
			
				if($author->id == $account->id || $account->isModerator() || $account->isOfficer()) {
				
				?><div class="float right"><form action="/forums/edit" method="post"><input type="hidden" name="post_id" value="<?php echo $post->id; ?>" />[<input type="submit" value="Edit" class="text" />]</form>
				<form action="/forums/delete" method="post"><input type="hidden" name="post_id" value="<?php echo $post->id; ?>" />[<input type="submit" value="Delete" class="text" />]</form></div><?php
					
				}
					
			} 
			
			?><div class="body_content<?php if($character->isModerator()) echo " moderator"; if($character->isOfficer()) echo " officer"; ?>"><?php echo $post->body; ?></div><?php
			
			/* Check if this post has been edited */
			if(isset($post->edit_time)) {
			
				/* Get the editor */
				$editor = $post->getEditor();
				
				/* Get the editors character */
				$editor_character = $editor->getPrimaryCharacter();
				
				/* Print out the updated statement */
				?><p class="italics<?php
				
				/* Check if the editor is a moderator */
				if($editor->isModerator()) echo " moderator";	
				
				/* Check if the editor is an officer */
				if($editor->isOfficer()) echo " officer";
				
				?>">Edited on <?php echo $post->getEditTime(); ?> by <?php echo $editor_character->name; ?>.</p><?php
				
			}
			
			if(isset($author->forum_signature)) {
				?><div class="signature">
					<?php echo $author->forum_signature; ?>
				</div><?php
			} ?>
		</div>
	</section>
	<div class="clear both"></div><?php
	
}

/* Check if the thread is locked */
if(isset($account)) {
	
	if($thread->isNotLocked() || $account->isOfficer() || $account->isModerator()) {
	
		$primary_class = $primary_character->getClass();
		$primary_race = $primary_character->getRace();
		$primary_rank = $primary_character->getRank(); 
		
		?><section class="reply"><hr />
			<div class="character">
				<p class="thumb<?php if($primary_character->isModerator()) { echo " moderator"; } if($primary_character->isOfficer()) { echo " officer"; } ?>"><a href="/roster/character/<?php echo $primary_character->name; ?>" class="noborder"><img src="<?php echo $primary_character->getThumbnail(); ?>" alt="Character Thumbnail" /></a></p>
				
				<p style="font-size: 1.2em !important;"><a href="/roster/character/<?php echo $primary_character->name; ?>" class="<?php echo $primary_class->slug; ?>"><?php echo $primary_character->name; ?></a></p>
				
				<p class="<?php echo $primary_class->slug; ?>"><?php echo $primary_character->level; ?> <?php echo $primary_race->name; ?> <?php echo $primary_class->name; ?></p>
				
				<p><a href="/roster/rank/<?php echo $primary_rank->slug; ?>"><?php echo $primary_rank->long_name; ?></a></p>
				
				<?php if($account->isModerator() && !$account->isOfficer()) {
					
					?><p class="moderator">Forum Moderator</p><?php
					
				} ?>
				
				<p><?php echo $primary_character->achievements; ?> <img src="/media/images/icons/achievements.gif" alt="Achievement Points" class="noborder" /></p>
			</div>
			<div class="body">
				<h2>Reply to Thread</h2>
				
				<form action="/forums/reply" method="post">
				
					<input type="hidden" name="thread" value="<?php echo $thread->id; ?>" />
					
					<p><textarea name="body" class="tinymce" rows="10"></textarea></p>
					<p><input type="submit" value="Post Reply" /></p>
				
				</form>
			</div>
		</section><?php
		
	} else {
		
		?><p class="info">This thread is locked and replies cannot be posted.</p><?php
		
	}
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>

