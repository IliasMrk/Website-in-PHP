<?php

function cleanFormPlaceholders() {
#form placeholders should be NULL before submission	
	$placeHolders = ['[+Admin+]'=>'',
					 '[+Academic+]'=>'',
					 '[+userTypeError+]'=>'',
					 '[+fName+]'=>'',
					 '[+fNameError+]'=>'',
					 '[+sName+]'=>'',
					 '[+sNameError+]'=>'',
					 '[+telephone+]'=>'',
					 '[+telephoneError+]'=>'',
					 '[+email+]'=>'',
					 '[+emailError+]'=>'',
					 '[+uName+]'=>'',
					 '[+uNameError+]'=>'',
					 '[+pwd+]'=>'',
					 '[+pwdError+]'=>''
					];
	return $placeHolders;
}



	
	function htmlHeading($text, $level) { #function to change heading level 
		$heading = strtolower($text);
		switch ($level) {	//loop for heading size
			case 1 :
			case 2 :
			case 3 :
			
				$heading = ucwords($heading); // uc words heading 
				break;
			
			case 4 :
			case 5 :
			case 6 :
				$heading = ucfirst($heading);  //for capital heading
				break;
			default:  // default heading if heading level is uknown
				$heading = '<FONT COLOR="#ff0000">Unknown heading level:' . $level . '</FONT>';
			}
		return '<h' . $level . '>' . htmlentities($heading) . '</h' . $level .  '>'; //sanitise and return
	}
	


function checkIfadminExists($pdo){ //function to check if admin already exists else it creates him
	
	
	$sql = "SELECT * FROM users
	WHERE fname='Admin'";
	try { 
		$stmt = $pdo->query($sql);
		$dataset = $stmt->fetchAll();
		
		if(empty($dataset)){ //if admin doesn't exist 
		createAdminAcc($pdo); // create first user (admin) in table users 
		
		}
		
} catch (PDOException $e) {
		$errorCode = $e->getCode();
		$errorMessage = $e->getMessage();
		echo "</p>$errorCode : $errorMessage</p>";
		
}


}

function createAdminAcc($pdo){ // function to insert admin to database 
	
	
		#Insert Admin
		
$sql = "INSERT INTO users(fname,sname,uname,password,userType) 
		VALUES ('Admin','User','ubadmin01','DCSadmin-01','Admin')";


try {
		$stmt = $pdo->query($sql); #use PDO query method to create Admin
		
		
} catch (PDOException $e) {
		$errorCode = $e->getCode();
		$errorMessage = $e->getMessage();
		echo "</p>$errorCode : $errorMessage</p>";
}
	
}

function validationCheck($data, $pdo, $formPlaceholders){ //validation form to check for errors

	$errors = array(); //array to hold errors
	$clean = array(); // array to hold clean data 
	
	
	
	if (checkUserExists(trim($data['userName']),$pdo)=== true) { // check if user exists in database 
		$clean = $data['userName'];
		$formPlaceholders['[+uNameError+]'] = "";
	} else if (checkUserExists(trim($data['userName']),$pdo)=== NULL) { //else return error 
		$errors['userName'] = "user unknown";
	}
	else{
		$errors['userName'] = "user unknown"; // if no data is supplied return error 
		$formPlaceholders['[+uNameError+]'] = htmlentities($errors['userName']);
	}
	if(checkPasswordCorrect(trim($data['pasword']),$pdo)===true){ //if password is correct store in clean data and continue 
		
		$clean = $data['pasword'];
		$formPlaceholders['[+pwdError+]'] = "";
	} else { //else return error 
		$errors ['pasword']= " Invalid password supplied";
		$formPlaceholders['[+pwdError+]'] = htmlentities($errors['pasword']);
	}
	
	return [$clean,$errors,$formPlaceholders]; // return data
}
	
	
	


function checkUserExists($uname,$pdo){ #determines if a given $uname exists in the users table
	
	
	
		$sql = "SELECT uname FROM users WHERE uname = :uname";
		$data = ['uname' => $uname]; #PDO execute method 
	try{
	
	$stmt = $pdo->prepare($sql);
	
	$stmt-> execute($data);
	$found=$stmt->fetch();
	return($found)?true : false;
	
	}
	catch(PDOException $e){
		$errorCode = $e->getCode();
		
		$errorMessage=$e->getMessage();
		return true;
		
	}
	
}
	
	function checkPasswordCorrect($password,$pdo){ //check if password matches 
		$sql = 'SELECT password FROM users WHERE password = :password;';
		$data = ['password' => $password]; #PDO execute method 
	try{
	$stmt = $pdo->prepare($sql);
	
	$stmt-> execute($data);
	$found=$stmt->fetch();
	return($found)?true : false;
	
	}
	catch(PDOException $e){
		$errorCode = $e->getCode();
		
		$errorMessage=$e->getMessage();
		return false;
		
	}
	}
	function checkUserType($uname,$pdo){#determines the userType of the user
	
	

		$sql = "SELECT userType FROM users WHERE uname = :uname";
		$data = ['uname' => $uname]; #PDO execute method 
	
	try{
	
	$stmt = $pdo->prepare($sql);
	
	$stmt-> execute($data);
	$found=$stmt->fetch();
	
	 
	if($found){ // if the user is found it returns the userType
		return($found['userType']);
	}
		else{ //else false 
			return false;
		}
	
	}
	catch(PDOException $e){
		$errorCode = $e->getCode();
		
		$errorMessage=$e->getMessage();
		return true;
		
	}
	}
function selectUser($userType,$pdo){ # select the name and surname of the user who is logging in 
	
		$sql = "SELECT fname,' ',sname FROM users WHERE userType = :userType";
		$data = ['userType' => $userType]; #PDO execute method  
	
	try{
	
	$stmt = $pdo->prepare($sql);
	
	$stmt-> execute($data);
	$found=$stmt->fetch();
		if($found){ // if its found return it 
			return($found['fname'].' '.
				   $found['sname'].' :');
	}
	else{ // else return false 
		return false;
	}
		 
	

	}
	catch(PDOException $e){
		$errorCode = $e->getCode();
		
		$errorMessage=$e->getMessage();
		return true;
		
	}
	
}
function getNav($userType){ // get nav according to the user type 
	if($userType == "Admin"){
		$nav = '<li><a href="index.php?view=Home">Home Page</a></li>
				<li><a href="index.php?view=ModuleResults" title="Available to academic and admin users after login.">Module Results</a></li>
				<li><a href="index.php?view=UserAdministration" title="Available to Admin users only.">User Administration</a></li>';
	}
	else if ($userType == "User"){
		$nav =  '<li><a href="index.php?view=Home">Home Page</a></li>
				<li><a href="index.php?view=ModuleResults" title="Available to academic and admin users after login.">Module Results</a></li>';
	}
	else{
		$nav =  '<li><a href="index.php?view=Home">Home Page</a></li>';
	}
	return $nav;
}
	


function readFiles($filesToHandle){ // read files from directory
	$dataLines = array(); //array to store the data
  foreach($filesToHandle as $file){ //function to loop through file names array 
	if(file_exists("data/$file.csv")){ // check if the file exists to avoid any possible errors
	$fileName = fopen("data/$file.csv",'r'); //open file for reading
	}
	else{ // otherwise return error 
		include 'views/404.php';
	}		
		    while(!feof($fileName)){ //read data till we reach the end of file
			$lineEntry = fgets($fileName); #file gets each line as a string
			
			$line = explode(',', $lineEntry); 
			$dataLines[] = $line; // store file data in an array 
			
			
			
	}
			
  }
  



	return $dataLines;



fclose($fileName); // closing the file 
	
	
}





function checkIfDataExist($pdo){// search module results table to see if there is any data inside

	
	$sql = "SELECT * FROM moduleResults";
	
	try { 
		$stmt = $pdo->query($sql);
		$dataset = $stmt->fetchAll();
		
		if(empty($dataset)){
			return false;
		}
		else{
			return true;
		}
		
} catch (PDOException $e) {
		$errorCode = $e->getCode();
		$errorMessage = $e->getMessage();
		echo "</p>$errorCode : $errorMessage</p>";
		
}


}

function createDataTable($modules,$students,$results,$pdo){	//insert data to module results table
	
	
	
		
$sql = "INSERT INTO moduleResults(moduleCode,studentID,moduleResult) 
		VALUES ('$modules', '$students', '$results')";


try {
		$stmt = $pdo->query($sql); #use PDO query method to insert data
		
		
} catch (PDOException $e) {
		$errorCode = $e->getCode();
		$errorMessage = $e->getMessage();
		echo "</p>$errorCode : $errorMessage</p>";
}
	
}

function buildTable($moduleName, $pdo){// build module results tables

$sql = "SELECT (CASE WHEN moduleResult >= 70 THEN '1st'
            WHEN moduleResult >= 60 THEN '2.1'
            WHEN moduleResult >= 50 THEN '2.2'
            WHEN moduleResult >= 45 THEN '3rd'
            WHEN moduleResult >= 40 THEN 'Pass'
			ELSE 'Fail'
	   END) AS Statistic,
	   count(CASE WHEN moduleResult >= 70 THEN '1st'
            WHEN moduleResult >= 60 THEN '2.1'
            WHEN moduleResult >= 50 THEN '2.2'
            WHEN moduleResult >= 45 THEN '3rd'
            WHEN moduleResult >= 40 THEN 'Pass'
			ELSE 'Fail'
	   END) as Number
FROM moduleResults
WHERE moduleCode = '$moduleName'
GROUP BY Statistic";

try{
	$stmt = $pdo->query($sql);
	
	
	$row=$stmt->fetchAll();
	
	if (true){ // if module name found in database 
	if($moduleName == "jv"){ // title depends on the module title
	$html = htmlHeading("Javascript",3);
	}else if ($moduleName == "p1"){
	$html = htmlHeading("Web programming using php",3);
	}else if ($moduleName == "dt"){
	$html = htmlHeading("Database technology",3);
	}
	$html .= "<table>";
	$html .= "<tr><td><strong>Statistics</strong></td>";
	$html .= "<td><strong>Number</strong></td></tr>";
		foreach($row as $data){ // build each row of the table
	
	
	$html .= "<tr><td>".$data['Statistic']."</td><td>".$data['Number']."</td></tr>";	
		}
	
	
	}else{
		return false;
	}
	
	}
	catch(PDOException $e){
		$errorCode = $e->getCode();
		
		$errorMessage=$e->getMessage();
		return false;
		
	}
// sql query to find the average and the total count of students 
$sql = "SELECT ROUND(AVG(moduleResult),0) as averageMark, 
	   COUNT(moduleResult) as countMarks
FROM moduleResults
WHERE moduleCode = '$moduleName'";

try{
	$stmt = $pdo->query($sql);
	
	
	$row=$stmt->fetchAll();
	
	if (true){
		foreach($row as $data){ //create rows for average and totals
	
	
	$html .= "<tr><td> Average Mark </td><td>".$data['averageMark']."</td></tr>";
		}
	
	$html .= "<tr><td> TOTAL students </td><td>".$data['countMarks']."</td></tr>";
	$html .= "</table>";
	return $html;
	}else{
		return false;
	}
	
	}
	catch(PDOException $e){
		$errorCode = $e->getCode();
		
		$errorMessage=$e->getMessage();
		return false;
		
	}
	return $html; 
}

function displayAllUsers($pdo){// display all user accounts found in database (users table)


$sql = "SELECT CONCAT(fname,' ',sname) as Name,
userid as ID
FROM users
ORDER by Name";

try { 
		$stmt = $pdo->query($sql);
		$dataset = $stmt->fetchAll();
		
		if(!empty($dataset)){ //create user Administration view 
		$html='<span style="">Add new User: </span><a href="index.php?view=UserAdministration&action=Add">ADD</a><table>'; // add a user button 
		$html .= "<table>";
		$html.= "<tr><th><strong>Name</th><th>View</th><th>Edit</th><th>Delete</th></tr>"; // user titles 
		foreach($dataset as $data){ // loop through users and create table 
		$id = $data['ID'];
		$html.= "<tr><th>$data[Name]</th>".
		"<th><a href="."index.php?view=UserAdministration&action=View&id=$data[ID]".">View</a></th>".
		"<th><a href="."index.php?view=UserAdministration&action=Edit&id=$data[ID]".">Edit</a></th>".
		"<th><a href="."index.php?view=UserAdministration&action=Delete&id=$data[ID]".">Delete</a></th></tr>";
		
		
		}
		$html.= "</table>";
		}
		return [$html,$id];
		
} catch (PDOException $e) {
		$errorCode = $e->getCode();
		$errorMessage = $e->getMessage();
		echo "</p>$errorCode : $errorMessage</p>";
		
}
	
	
	
}


function viewUser($id,$pdo){ // view user based on the id 


$sql = "SELECT * FROM users
WHERE userid = $id";

try { 
		$stmt = $pdo->query($sql);
		$dataset = $stmt->fetchAll();
		
		if(!empty($dataset)){ // create table for user view 
		
		$html = "<table>";
		$html.="<tr><th>userID</th><th>surname</th><th>email</th><th>email</th><th>telephone</th><th>username</th><th>password</th><th>userType</th></tr>";
		foreach($dataset as $data){
		$html.= "<tr><th>$data[userid]</th><th>$data[fname]</th><th>$data[sname]</th><th>$data[email]</th><th>$data[telephone]</th><th>$data[uname]</th><th>$data[password]</th><th>$data[userType]</th></tr>";
		
		}
		
		$html.= "</table>";
		}
		return $html;
		
} catch (PDOException $e) {
		$errorCode = $e->getCode();
		$errorMessage = $e->getMessage();
		echo "</p>$errorCode : $errorMessage</p>";
		
}
	
	
	
}











function validateFormData($formData, $pdo, $formPlaceholders) {
#process the submitted form data in $formData array
	$cleanData = array(); #array to hold form data which passes validation
	$errors = array();	  #array to hold error messages to display next to form elements
	if (validUserType(trim($formData['userType']))) {
		$cleanData['userType'] = trim($formData['userType']); #store in clean data array
		#following code sets the previously selected SELECT pull down menu option
		switch ($cleanData['userType']) {
			case 'Admin' :
				$formPlaceholders['[+Admin+]'] = 'selected';
				break;
			case 'Academic' :
				$formPlaceholders['[+Academic+]'] = 'selected';
				break;
		}
		$formPlaceholders['[+userTypeError+]'] = "";
	} else {
		$errors['userType'] = " is not a valid useType";
		$formPlaceholders['[+userTypeError+]'] = htmlentities($errors['userType']);
	}
	if (validName(trim($formData['firstName']))) {
		$cleanData['firstName'] = trim($formData['firstName']); #store in clean data array
		$formPlaceholders['[+fName+]'] = htmlentities($cleanData['firstName']);
		$formPlaceholders['[+fNameError+]'] = "";
	} else {
		$errors['firstName'] = " Alphabetical and no spaces";
		$formPlaceholders['[+fName+]'] = $formData['firstName']; #show incorrect data in form field
		$formPlaceholders['[+fNameError+]'] = htmlentities($errors['firstName']);
	}
	if (validSurName(trim($formData['surname']))) {
		$cleanData['surname'] = trim($formData['surname']); #store in clean data array
		$formPlaceholders['[+sName+]'] = htmlentities($cleanData['surname']);
		$formPlaceholders['[+sNameError+]'] = "";
	} else {
		$errors['surname'] = " Alphabetical";
		$formPlaceholders['[+sName+]'] = $formData['surname']; #show incorrect data in form field
		$formPlaceholders['[+sNameError+]'] = htmlentities($errors['surname']);
	}
	if (validUserName(trim($formData['username']),$pdo)) {
		$cleanData['username'] = trim($formData['username']); #store in clean data array
		$formPlaceholders['[+uName+]'] = htmlentities($cleanData['uName']);
		$formPlaceholders['[+uNameError+]'] = "";
	} else {
		$errors['username'] = " Alphanumeric, >=8 and no spaces";
		$formPlaceholders['[+uName+]'] = $formData['username']; #show incorrect data in form field
		$formPlaceholders['[+uNameError+]'] = htmlentities($errors['username']);
	}
	if (validPassword(trim($formData['password']))) {
		$cleanData['password'] = trim($formData['password']); #store in clean data array
		$formPlaceholders['[+pwd+]'] = htmlentities($cleanData['password']);
		$formPlaceholders['[+pwdError+]'] = "";
	} else {
		$errors['password'] = " Invalid password supplied";
		$formPlaceholders['[+pwd+]'] = $formData['password']; #show incorrect data in form field
		$formPlaceholders['[+pwdError+]'] = htmlentities($errors['password']);
	}
	
	
	
	return [$cleanData,$errors,$formPlaceholders];
}












function validUserType($userType) {#white list validation of person titles

	$validUserTypes = array('Admin', 'Academic');
	if (in_array($userType, $validUserTypes)) {
		return true;
	} else {
		return false;
	}
}




function validName($name) {#a name should be a-z or A-Z characters 
	
	if (ctype_alpha($name)) {
		return true;
	} else {
		return false;
	}
}
function validSurName($name) {	#a surname should be alphabetic and can include spaces

	if ((ctype_alpha($name))||(ctype_alpha($name)) AND (ctype_space($name)==true)){
		return true;
	} else {
		return false;
	}
}
function validUserName($name,$pdo) {#a username should be unique, alphanumeric with no spaces and be >= 8
	
	$length = strlen($name);
	
	if (($length >=8) and (ctype_alnum($name) and (ctype_space($name)==false)) and uniqueUname($name,$pdo)) {
		return true;
	} else {
		return false;
	}
}
function validPhone($name) {#a phone number should be alphanumeric
	
	
	if (ctype_alnum($name)) {
		return true;
	} else {
		return false;
	}
}

function uniqueUname($uname,$pdo){ //check if the username is unique 
	$sql = "SELECT uname FROM users WHERE uname = :uname;";
	$data = ['uname' => $uname]; #PDO execute method requires data in an array
	try{
	$stmt = $pdo->prepare($sql);
	
	$stmt-> execute($data);
	$row=$stmt->fetch();
	return(empty($row))?true : false;
	}
	catch(PDOException $e){
		$errorCode = $e->getCode();
		
		$errorMessage=$e->getMessage();
		return false;
		
	}

}

function validPassword($name) { // check if the password is valid 
	$length = strlen($name);
	
	if (($length >=8) and (ctype_space($name)==false) and (preg_match('/[A-Z]+/', $name) and preg_match('/[a-z]+/', $name) and preg_match('/[\!-< > £ $ % & * ~ #]+/', $name))){
				return true;
	}
	else {
		return false;
	}
}



function displayArray($array, $name) { //function to display and array in html 
	return '<pre>' . $name. print_r($array,true) .	'</pre>';
}




?>