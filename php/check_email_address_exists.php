<?php
// include constant file
include('constant.php');

// Get email address
$check_exists = $_POST;

if(!empty($check_exists)) //check input values are not empty
{
	// if not empty check then check given email address already exists or not

	// Create connection
	$dbconnection = new mysqli($servername, $username, $password);

	// Check db exists or not
	$dbexists = $dbconnection->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
	$dbexists->bind_param("s", $dbname);
	$dbexists->execute();
	$dbexists->bind_result($data);
	if($dbexists->fetch()) //if exists
	{
		// connect with database
		$dbconnection = new mysqli($servername, $username, $password);
		$dbconnection->select_db($dbname);

		$emailaddressexists = $dbconnection->prepare("SELECT id FROM `users` WHERE email_address = ?");
		$emailaddressexists->bind_param("s", $email_address);

		$email_address = $check_exists['email_address'];//get input value
		$emailaddressexists->execute(); //execute the query

		$emailaddressexists->bind_result($data); //get result

		if($emailaddressexists->fetch())//if exists return already exists or else not
			echo 'false';
		else
			echo 'true';
	}
	else
		echo 'true';
	
}

?>