<?php
require_once('includes/functions.php'); //including the functions 
	
	$headTitle = 'Access Error'; // title 
	
	$viewHeading = htmlHeading("Invalid Access Attempted To Website",3); //top heading 
	$content = "<p><strong> Error  Page not found or forbidden</strong></p>";
		
	$nav = "";
	$login =  $_SESSION['loginStatus'];


?>