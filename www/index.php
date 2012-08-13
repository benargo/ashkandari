<?php # index.php

/* 
 * This is the website home page.
 * It's primary function is to display the latest news articles
 * coupled with links to additional sections of the website.
 */
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the head of the page
require_once('../framework/config.php');

require(PATH.'framework/head.php');

/* Get basic information about the guild from Battle.net */
if( $json = file_get_contents("http://eu.battle.net/api/wow/guild/Tarren-Mill/Ashkandari") ) {

	$guild = json_decode($json);	

}

?><h1>Welcome to Ashkandari</h1>
<p>Ashkandari is a<?php if( isset($guild) ) { echo ' level '. $guild->level; } ?> social and casual raiding guild based on Tarren Mill. We have two raiding teams with very mature and fun people. We always have fun together and look out for each other. Ashkandari started out in life on the realm of Spinebreaker in 2010 as a guild called Insert Coin. In 2012 we moved to Tarren Mill and are still going strong.</p>
<h2>News</h2><?php

// Here is the news section of our front page. We need to display the three most recent news articles.
if( $items = news_item::getArticles(3) ) {
	
	// Loop through each of the news items
	foreach($items as $item_id) {
		
		// Create a new news item object from the given ID number
		$item = new news_item($item_id);
		
		// Before we start printing we need to generate an instance of the character that wrote this particular article, as we need it later on.
		$character_id = $item->getAuthor();
		$character = new character($character_id);
		
		// Now we can start printing out the news item
		?><section class="news_item" id="<?php echo $item->id; ?>">
			<h2><a href="/news/<?php echo $item->id; ?>" title="View the full article and any associated comments"><?php echo $item->title; ?></a></h2>
			<p class="italics">Written by <a href="/roster/character/<?php echo strtolower($character->name); ?>"><?php echo $character->name; ?></a> on <?php echo $item->getDate(); ?> at <?php echo $item->getTime(); ?>.<?php
			if( $item->commentsAllowed() ) {
				
				// If we've dropped into here it means that comments are allowed
				?> (<a href="/news/<?php echo $item->id; ?>#comments" title="Skip to the comments"><?php echo $item->countComments(); ?> comments</a>)<?php
				
			} ?></p>
			<?php echo $item->content; // We can simply slap it in, as it will be stored as HTML in the database ?>
		</section>
		<?php
	} 	
	
} else {
	
	?><p>Sorry, there are no news items to display.</p><?php
	
}

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>