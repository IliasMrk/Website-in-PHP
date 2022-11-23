<?php

require_once('includes/functions.php'); //including the functions 



	
	$headTitle = 'Module Results'; // title 
	
	$viewHeading = htmlHeading("Module Results",2); //top heading 
	
	#build each table, assign a css class tables to style the content
	$content ='<div class = "tables">' .buildTable("dt", $pdo) .'</div>'; 
	$content .= '<div class = "tables">'.buildTable("jv", $pdo).'</div>';
	$content .= '<div class = "tables">'.buildTable("p1", $pdo).'</div>';
	$nav = getNav($_SESSION['userType']); //nav according to user type
	$login =  $_SESSION['loginStatus']; // login status
	
	
	
?>
	