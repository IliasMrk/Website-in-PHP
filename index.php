<?php 
	
require_once 'includes/config.php';
require_once 'includes/functions.php';
	$files_to_handle = array("dt","jv","p1"); // array with file names to be read
	checkIfadminExists($pdo); // function to check if admin exists 
	$fileLines = readFiles($files_to_handle); // function to read from files 
	$dataTableExists = checkIfDataExist($pdo); // check if Module results table exists in database
	
	if($dataTableExists == false){ // if it doesn't exist, insert the data read from files to database
	foreach($fileLines as $line){
			$moduleCode = trim($line[0]);
			$studentId = trim($line[1]);
			$moduleResult = trim($line[2]);
		createDataTable($moduleCode,$studentId,$moduleResult,$pdo);	// create table 
	}
	}
			
	
	session_start();                                                                                                                                                                                                                                                                    
	
	$myPlaceholders = cleanFormPlaceholders(); //placeholders for incorrect entries 
	$_SESSION['loginStatus']= str_replace(array_keys($myPlaceholders), array_values($myPlaceholders), file_get_contents('html/login.html')); //empty placeholders before submitting 
	
	
	if(isset($_POST['logout'])){ //if user presses logout destroy the session and redirect to the home page
		
		session_destroy();
		header('location:index.php');
		
		
	
		
	}
	

	
	 if((!empty($_SESSION['userType'])) AND ($_SESSION['userType'] != "Public")){ // if user is logged in 
		
		 
		 $_SESSION['loginStatus'] = str_replace('[+userName+]',selectUser($_SESSION['userType'],$pdo),file_get_contents('html/logout.html'));
	 }
		
		else if (isset($_POST['login'])){  // if no user is logged in and login button is pressed 
	
	
		$formData = validationCheck($_POST, $pdo, $myPlaceholders); //do a validationCheck
		 
		$clean = $formData[0]; // store clean data in a variable 
		$errors = $formData[1]; //store errors in a variable 
		$formPlaceholders = $formData[2]; // #replace all placeholders in form string with matching placeholder data 
		
		
		if((isset($_POST['login']))AND ($clean) AND (!$errors)){ // if there are no errors 
			 $_SESSION['userType'] = checkUserType($_POST['userName'],$pdo); // assign the user type  
			 $_SESSION['loginStatus'] = str_replace('[+userName+]',selectUser($_SESSION['userType'],$pdo),file_get_contents('html/logout.html')); // login status 
		}
		else{ // 
		
		$_SESSION['loginStatus']= str_replace(array_keys($myPlaceholders), array_values($formPlaceholders), file_get_contents('html/login.html')); // replace placeHolders with errors 
		
		}
		}
	else{
		$_SESSION['userType'] = "Public";
		$_SESSION['loginStatus']= str_replace(array_keys($myPlaceholders), array_values($myPlaceholders), file_get_contents('html/login.html'));  // if user not logged in get login menu 
	}
	
	
	
	
	
	
	if((!isset($_GET['view']))){ //if the view is not set then we see the Home page 
		$id = 'Home';
	}else{
		$id = $_GET['view']; //otherwise the chosen from the list below 
	}
	switch ($id){ 
		case 'Home':	
		include 'views/public.php';
		break;
		case $id == 'ModuleResults' AND $_SESSION['userType'] != 'Public':				
		include 'views/ModuleResults.php';
		break;
		case $id == 'UserAdministration' AND $_SESSION['userType'] == 'Admin':	
		include 'views/UserAdministration.php';
		break;
		default:
		include 'views/404.php';
	}
	
	
	
		
	
		
	
		
	
		
	    $placeHolders = ['[+title+]','[+heading+]','[+content+]','[+nav+]','[+login+]']; //page placeholders
		$values = [$headTitle, $viewHeading, $content,$nav,$login]; // values which will replace the placeholders 
		$template = file_get_contents('html/mockupPage.html'); //template of html page 
		
		$html = str_replace($placeHolders,$values,$template); // replacing the html placeholders with the php values 
		echo $html;
		
		?>