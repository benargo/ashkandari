<?php // account/characters/set-primary.php

/* 
 * This page sets the primary character for the account
 */

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
		header("Location: /account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

// Create an account object
$account = new account($_SESSION['account']);

// Set the primary character
$account->setPrimaryCharacter($_GET['id']);

// Create a new character object
$character = new character($_GET['id']);

$_SESSION['new_primary_character'] = $_GET['id'];

if($_SERVER['REQUEST_URI'] == "/account/characters/set-primary/". $_GET['id']) {
	
	header("location: /account/");
	
}