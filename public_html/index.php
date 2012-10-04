<?php

/**
 * Welcome to Ashkandari
 * To the server, this document routes absolutely everything possible.
 * However, what it really does is include other documents which help
 * us render the application's full potential.
 */

// Load the framework!
require_once('global/loader.php');

// Create the page
$page = new Page;

// Render the page
$page->render();