<?php
	
		require_once'includes/functions.php'; //including the functions 
	
	
	$headTitle= "Home Page";
	$viewHeading = htmlHeading("Department of Computer Science ",2);
	$content= "<p>The Department of Computer Science and Information Systems at Birkbeck is one of the first computing departments established in the UK, celebrating our 64th anniversary in 2021. We provide a stimulating teaching and research environment for both part-time and full-time students, and a friendly, inclusive space for learning, working, and collaborating.</p>
		<p>This website is the solution to the Web Programming Using PHP module - Final Marked Assignment.</p>
		<p>You need to successfully login to access module results and/or user administration depending on your user type.</p>";
	
	

	$nav = getNav($_SESSION['userType']); //nav according to user
	$login = $_SESSION['loginStatus']; // login status
	
	
	
	
	
	
		
	
	
?>