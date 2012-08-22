<?php // legal/battlenet.php

/* 
 * Battle.net Usage Information
 */
 
// Switch HTTPS off
if( isset($_SERVER['HTTPS']) ) {
	header('Location: http://www.ashkandari.com'. $_SERVER['REQUEST_URI']);
}

// Require the head of the page
require_once('../../framework/config.php');

$page_title = "Battle.net Usage Information";

require(PATH.'framework/head.php');

?><h1>Usage of the Blizzard Community Platform API</h1>

<p>Some of the services provided by Ashkandari Web Services (hereafter "Ashkandari"), a non-profit individual operation operating within the United Kingdom of Great Britain and Northern Ireland (hereafter the "United Kingdom"), use data obtained from the Blizzard Community Platform API, and is subject to their <a href="http://blizzard.github.com/api-wow-docs/#idp57480" target="_blank">API Policy</a>.</p>

<p>We are obligated to provide the source code that communicates with Battle.net. You can find this code and its development history <a href="https://github.com/benargo/Ashkandari/">published online</a>.</p>

<p>We are obligated to disclaim any association with, or endorsement by, Blizzard Entertainment SAS. All related data, materials, logos, and images are copyright &copy; Blizzard Entertainment SAS.</p>

<p>Blizzard Entertainment SAS, TSA 60 001, 78008 Versailles Cedex, France, is a division of Blizzard Inc., identified at the SIREN under the number 489 952 457 RCS Versailles.</p>

<p class="italics">This information was last updated on <strong>August 22, 2012</strong>.</p><?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>