<?php # 404.php

/*
 * This is the website's HTTP 404 error page.
 */

// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://www.ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the head of the page
require_once('../framework/config.php');

$page_title = "Page Not Found";

require(PATH.'framework/head.php');

?><h1>Page not Found</h1>

<p>Sorry, we could not find the page you're looking for. Please try going elsewhere.</p>

<?php
// Require the foot of the page
require(PATH.'framework/foot.php'); ?>