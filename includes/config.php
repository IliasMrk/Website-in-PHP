<?php
#set up connection parameters
$host = 'mysqlsrv.dcs.bbk.ac.uk';	#host name
$db   = 'imarko01db'; 				#database name 
$user = 'imarko01';				#username loginID
$pass = 'bbkmysql';					#password
$charset = 'utf8mb4';				#define character set to be used
#create the Data Source Name for a MySQL connection using above variables
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
#driver-specific connection options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
#CONNECT to the database
try {
		$pdo = new PDO($dsn, $user, $pass, $options);
		$databaseConnected = true;
	
} catch (PDOException $e) {
		$databaseConnected = false;
		$errorMessage = $e->getMessage();
		echo "</p>FAILED to Connect to database: $db</p>";
		echo "</p>check your connection parameters.</p>";
		echo "</p>PDOexception message: $errorMessage</p>";
}


#CREATE a new table for users if it doesn't already exist

$sql = "CREATE TABLE IF NOT EXISTS users (
			userid int(11) NOT NULL AUTO_INCREMENT,
			fname varchar(30) NOT NULL,
			sname varchar(45) NOT NULL,
			email varchar(45),
			telephone varchar(30),
			uname varchar(30) NOT NULL,
			password varchar(15) NOT NULL,
			userType varchar(10) NOT NULL,
			PRIMARY KEY (userid),
			UNIQUE KEY (uname)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
try {
		$stmt = $pdo->query($sql); #use PDO query method to create table from $sql
		
} catch (PDOException $e) {
		$errorMessage = $e->getMessage();
		echo "</p>FAILED to create table: users</p>";
		echo "</p>check your SQL.</p>";
		echo "</p>PDOexception message: $errorMessage</p>";
	
}

#CREATE a new table for Module Results if it doesn't already exist
 
$sql = "CREATE TABLE IF NOT EXISTS moduleResults (
			moduleCode varchar(2) NOT NULL,
			studentID varchar(8) NOT NULL,
			moduleResult INT NOT NULL,
			PRIMARY KEY (moduleCode,studentID)
	
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
try {
		$stmt = $pdo->query($sql); #use PDO query method to create table from $sql
		
} catch (PDOException $e) {
		$errorMessage = $e->getMessage();
		echo "</p>FAILED to create table: Module Results</p>";
		echo "</p>check your SQL.</p>";
		echo "</p>PDOexception message: $errorMessage</p>";
	
}






?>