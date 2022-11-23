<?php


require_once('includes/functions.php'); //including the functions 
	
	$headTitle = 'User Administration'; // title 
	
	$viewHeading = htmlHeading("User Administration",2); //top heading 
	
		
	$nav = getNav($_SESSION['userType']); // nav according to user type
	$login =  $_SESSION['loginStatus']; // login status
	
	
	
	
	
	
	
	
	
	$allUsers = displayAllUsers($pdo); //store users found in database 
	
	
	
	
	
	
	
	
	if(!isset($_GET['action'])){ //if the view is not set then we see the Home page 
		$action = 'userAdministration';
	}else{
		$action = $_GET['action']; //otherwise the chosen from the list below 
		
	}
	switch ($action){ 
		case 'userAdministration':	
		
		$content = $allUsers[0]; // displays the table with users created from the function 
		break;
		case 'Add':			
		$content = file_get_contents( 'html/form.html');
		break;
		case 'View':
		$id = $allUsers[1];  // assigns the id retrieved 
		$content = viewUser($id,$pdo); // displays the user on the specific view 
		break;
		case 'Edit':			
		$content = "";
		break;
		case 'Delete':			
		$content = "";
		break;
		default:
		include 'views/404.php';
	}
	
	
	
	$template = file_get_contents('html/form.html');

	$myPlaceholders = cleanFormPlaceholders();
	$cleanData = array();
	$errors = array();

if (isset($_POST['Submitted'])) { #data has been submitted to the form, data in $_POST to be checked
	
	#returns  clean, errors and placeholders arrays
	$formData = validateFormData($_POST, $pdo, $myPlaceholders); #validate all the submitted form data
	#extract clean data, errors and placeholders and placeholder data from arrays returned
	$cleanData = $formData[0];
	$errors = $formData[1];
	$formPlaceholders = $formData[2]; #populated form placeholders array after validation
	str_replace($errors,$formPlaceholders,$template);
	
	}
	
	
	
?>
	