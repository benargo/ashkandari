<?php /* head.php */

/* This file both prints out the start of the template, at the bottom of this document,
 * and runs a number of pre-flight checks to ensure everything is in order. */

// Check if there is an account session
if( isset($_SESSION['account']) ) {
	
	// If there is one, then we can set $account to the id number of that session
	$account = new account($_SESSION['account']);
	$primary_character = $account->getPrimaryCharacter();
	
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<!-- Meta Information -->
		<meta charset="UTF-8" />
		<meta name="robots" content="all" />
		<meta name="author" content="Ashkandari" />
		
		<!-- Search Engine Meta -->
		<meta name="keywords" content="Ashkandari, Tarren Mill, Warcraft, Mists, Pandaria" />
		<meta name="description" content="World of Warcraft guild Ashkandari (formerly Insert Coin) on Tarren Mill" />
		
		<!-- Page Title -->
		<title><?php if($page_title) { echo "$page_title - "; } ?>Ashkandari - Tarren Mill</title>
		
		<!-- Shortcut Icons -->
		<link type="image/x-icon" rel="shortcut icon" href="/media/images/favicon.ico" />
		<link rel="apple-touch-icon" href="/media/images/iphone-icon.png" />
		
		<!-- Core Stylesheets -->
		<link type="text/css" rel="stylesheet" media="screen and (min-device-width:641px)" href="/media/css/screen.css" />
		<link type="text/css" rel="stylesheet" media="screen and (min-device-width:0px) and (max-device-width:640px)" href="/media/css/mobile.css" />
		<link type="text/css" rel="stylesheet" media="print" href="/media/css/print.css" />
		
		<!-- Core JavaScript -->
		<script type="text/javascript" src="/media/scripts/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="<?php echo $protocol; ?>://static.wowhead.com/widgets/power.js"></script>
		
		<!-- Additional Material (if any) -->
		<?php
			// Additional Stylesheet Handler
			// This chunk of code here decides whether we need any additional stylesheets
			switch( $_SERVER['SCRIPT_NAME'] ) {
				
				case '/apply/index.php':
					echo '<link type="text/css" rel="stylesheet" href="/media/css/ui-lightness/jquery-ui-1.8.22.custom.css" />';
					echo '<link type="text/css" rel="stylesheet" href="/media/css/apply.css" />';
					break;
					
				case '/apply/verify.php':
					echo '<link type="text/css" rel="stylesheet" href="/media/css/ui-lightness/jquery-ui-1.8.22.custom.css" />';
					echo '<link type="text/css" rel="stylesheet" href="/media/css/apply.css" />';
					break;
					
				case '/roster/character.php':
					echo '<link type="text/css" rel="stylesheet" href="/media/css/ui-progress-bar.css" />';
					break;
					
				case '/account/register/index.php':
					echo '<link type="text/css" rel="stylesheet" href="/media/css/ui-lightness/jquery-ui-1.8.22.custom.css" />';
					break;
					
				case '/account/register/verify.php':
					echo '<link type="text/css" rel="stylesheet" href="/media/css/ui-lightness/jquery-ui-1.8.22.custom.css" />';
					break;
					
				case '/account/index.php':
					echo '<link type="text/css" rel="stylesheet" href="/media/css/ui-lightness/jquery-ui-1.8.22.custom.css" />';
					echo '<link type="text/css" rel="stylesheet" href="/media/css/account.css" />';
					break;
					
				case '/forums/application.php':
					echo '<link type="text/css" rel="stylesheet" href="/media/css/ui-progress-bar.css" />';	
					echo '<link type="text/css" rel="stylesheet" href="/media/css/tinymce.css" />';
					break;
					
				case '/forums/thread/new.php':
					echo '<link type="text/css" rel="stylesheet" href="/media/css/tinymce.css" />';
					break;
				
				case '/forums/thread/view.php':
					echo '<link type="text/css" rel="stylesheet" href="/media/css/tinymce.css" />';
					break;
					
				case '/forums/post/edit.php':
					echo '<link type="text/css" rel="stylesheet" href="/media/css/tinymce.css" />';
					break;
				
			}
			
			// Script handler
			// This chunk of code here decides whether we need to add any JavaScript into this template.
			switch( $_SERVER['SCRIPT_NAME'] ) {
				
				case '/apply/index.php':
					echo '<script type="text/javascript" src="/media/scripts/jquery-ui-1.8.22.custom.min.js"></script>';
					echo '<script type="text/javascript" src="/media/scripts/jquery.autosize.js"></script>';
					echo '<script type="text/javascript" src="/media/scripts/apply.js"></script>';
					break;
					
				case '/apply/verify.php':
					echo '<script type="text/javascript" src="/media/scripts/jquery-ui-1.8.22.custom.min.js"></script>';
					echo '<script type="text/javascript" src="/media/scripts/apply.js"></script>';
					break;
					
				case '/roster/index.php':
					echo '<script type="text/javascript" src="/media/scripts/jquery.tablesorter.js"></script>';
					break;
					
				case '/roster/race.php':
					echo '<script type="text/javascript" src="/media/scripts/jquery.tablesorter.js"></script>';
					break;
					
				case '/roster/class.php':
					echo '<script type="text/javascript" src="/media/scripts/jquery.tablesorter.js"></script>';
					break;
					
				case '/roster/rank.php':
					echo '<script type="text/javascript" src="/media/scripts/jquery.tablesorter.js"></script>';
					break;
					
				case '/roster/epgp.php':
					echo '<script type="text/javascript" src="/media/scripts/jquery.tablesorter.js"></script>';
					break;
					
				case '/roster/character.php':
					echo '<script type="text/javascript" src="/media/scripts/jquery.autosize.js"></script>';
					break;
					
				case '/account/register/index.php':
					echo '<script type="text/javascript" src="/media/scripts/jquery-ui-1.8.22.custom.min.js"></script>';
					echo '<script type="text/javascript" src="/media/scripts/chars-combobox.js"></script>';
					break;
					
				case '/account/register/verify.php':
					echo '<script type="text/javascript" src="/media/scripts/jquery-ui-1.8.22.custom.min.js"></script>';
					echo '<script type="text/javascript" src="/media/scripts/chars-combobox.js"></script>';
					break;
					
				case '/account/index.php':
					echo '<script type="text/javascript" src="/media/scripts/jquery-ui-1.8.22.custom.min.js"></script>';
					echo '<script type="text/javascript" src="/media/scripts/chars-combobox.js"></script>';
					echo '<script type="text/javascript" src="/media/scripts/account.js"></script>';
					break;
					
				case '/account/forums/signature.php':
					echo '<script type="text/javascript" src="/media/scripts/tiny_mce/jquery.tinymce.js"></script>';
					break;
					
				case '/officers/epgp/index.php':
					echo '<script type="text/javascript" src="/media/scripts/jquery.autosize.js"></script>';
					break;
					
				case '/forums/application.php':
					echo '<script type="text/javascript" src="/media/scripts/tiny_mce/jquery.tinymce.js"></script>';
					echo '<script type="text/javascript" src="/media/scripts/jquery.autosize.js"></script>';
					break;
				
				case '/forums/thread/new.php':
					echo '<script type="text/javascript" src="/media/scripts/tiny_mce/jquery.tinymce.js"></script>';
					echo '<script type="text/javascript" src="/media/scripts/jquery.autosize.js"></script>';
					break;
					
				case '/forums/thread/view.php':
					echo '<script type="text/javascript" src="/media/scripts/tiny_mce/jquery.tinymce.js"></script>';
					echo '<script type="text/javascript" src="/media/scripts/jquery.autosize.js"></script>';
					break;
					
				case '/forums/post/edit.php':
					echo '<script type="text/javascript" src="/media/scripts/tiny_mce/jquery.tinymce.js"></script>';
					echo '<script type="text/javascript" src="/media/scripts/jquery.autosize.js"></script>';
					break;
				
			}
		?>
	</head>
	<body>
		<div id="wrapper-top">
			<div id="wrapper-bottom">
				<div id="wrapper">
					<div id="service">
						<p class="service-cell"><?php
							if( isset($account) ) {
								?>Welcome <?php echo $primary_character->name; ?>. <a href="/account/logout?ref=<?php echo $_SERVER['REQUEST_URI']; ?>">Log out</a><?php	
							} else {
								?><a href="/account/login?ref=<?php echo $_SERVER['REQUEST_URI']; ?>">Log in</a> or <a href="/account/register/">Create an Account</a><?php
							}
						?></p>
					</div>
					<header>
						<a href="/" title="Home" id="wowlogo">Ashkandari</a>
						<!-- Primary Navigation -->
						<nav id="primary">
							<ul><?php
							echo '<li><a href="/" title="Home">Home</a></li>';
							echo '<li><a href="/roster/epgp" title="EPGP Standings">EPGP</a></li>';
							echo '<li><a href="/roster/" title="Guild Roster">Guild Roster</a></li>';
							echo '<li><a href="/forums/" title="Forums">Forums</a></li>';
							echo '<li><a href="/teamspeak/" title="TeamSpeak">TeamSpeak</a></li>';
							if ( isset($account) ) {
									
								echo '<li><a href="/account/" title="My profile, account settings and characters">My Account</a></li>';
															
							} else {
							
								echo '<li><a href="/apply/" title="Apply now to join Ashkandari">Apply</a></li>';
								
							}
							?></ul>
						</nav>
						<!-- /Primary Navigation -->
					</header>
					<div id="content-wrapper">
						<article>