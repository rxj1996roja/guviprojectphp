<?php
include('constant.php');

// Get input details
$registration_values = $_POST;
if(!empty($registration_values)) //check input values are not empty
{
	// if not empty then proceed
	// Create connection
	$dbconnection = new mysqli($servername, $username, $password);

	// Create DB if not exists
	$dbcreation = $dbconnection->prepare("CREATE DATABASE IF NOT EXISTS ".$dbname);
	$dbcreation->execute();
	
	// connect with database
	$dbconnection->select_db($dbname);

	// Check table already created or not
	$get_table_records = $dbconnection->prepare("CREATE TABLE IF NOT EXISTS `users` (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,email_address VARCHAR(30) NOT NULL,password VARCHAR(30) NOT NULL)");
	$result = $get_table_records->execute();

	// Insert new user
	$register_user = $dbconnection->prepare("INSERT INTO users (email_address, password) VALUES (?, ?)");
	$register_user->bind_param("ss", $email_address, $password);

	// set parameters and execute
	$email_address = $_POST['email_address'];
	$password = base64_encode(base64_encode($_POST['password']));
	$insert_result = $register_user->execute();

	require '../vendor/autoload.php';

	$redis = new Predis\Client(); //create an instance
	
	$user = ([
		"email_address"=>$email_address,
		'id'=>$dbconnection->insert_id
	]);
	// Set values to redis
	$redis->set("users", json_encode($user));
				
	// retun to profile page
	echo json_encode(['status'=>'true','id'=>$dbconnection->insert_id]);

}

?>