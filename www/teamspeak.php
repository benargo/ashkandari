<?php // teamspeak.php

/* 
 * This is the website page for the TeamSpeak handler.
 */
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the head of the page
require_once('../framework/config.php');

$page_title = "TeamSpeak";

require(PATH.'framework/head.php');

?><h1>TeamSpeak</h1><?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>