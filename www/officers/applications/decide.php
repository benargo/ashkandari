<?php

// Require the head of the page
require_once('../../../framework/config.php');

print_r($_POST);
exit;

/* Get the new forum thread */
$application = new application($_POST['id']);

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
	header("Location: /account/login?ref=/applications/". $application->id);
	
}

$account = new account($_SESSION['account']);

if($account->isOfficer()) {

	$application->decide($_POST['decision']);
	
}

/* Return to the thread */
header("Location: /applications/". $application->id);

?>