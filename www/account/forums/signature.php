<?php // account/forum-signature.php

/* 
 * This is the page where people can edit their forum signature.
 */
 


	
}

// Require the framework files
require_once('../../../framework/config.php');

// Check if we're already logged in
if(empty($_SESSION['account'])) {
	
		header("Location: /account/login?ref=". $_SERVER['REQUEST_URI']);
	
}

// Set the page title
$page_title = "Edit Your Forum Signature";

// Require the head of the document
require(PATH.'framework/head.php'); 

?><h1>Edit Your Forum Signature</h1>

<form action="/account/forums/signature-save.php" method="post">
	
	<p><textarea name="signature" id="wysiwyg" rows="10"><?php if(isset($account->forum_signature)) {
		echo $account->forum_signature;
	} ?></textarea></p>
	<p><input type="submit" value="Save Signature" /></p>
	
</form>
<script type="text/javascript"><!--
(function($) {
	$(document).ready(function() {
		$('#wysiwyg').wysiwyg();
	});
})(jQuery);
--></script>
<?php

// Require the foot of the page
require(PATH.'framework/foot.php'); ?>