<?php
// include constant file
include('constant.php');

// Get input details
$login_values = $_POST;
$error_values = json_encode(['status'=>'false','id'=>null]);
if(!empty($login_values)) //check input values are not empty
{
	// if not empty check given details are valid or not
	// Create connection
	$dbconnection = new mysqli($servername, $username, $password);

	// Check db exists or not
	$dbexists = $dbconnection->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
	$dbexists->bind_param("s", $dbname);
	$dbexists->execute();
	$dbexists->bind_result($data);
	if($dbexists->fetch()) //if exists
	{
		// Create connection
		$dbconnection = new mysqli($servername, $username, $password);
		$dbconnection->select_db($dbname);

		$checklogin = $dbconnection->prepare("SELECT id,password FROM `users` WHERE email_address = ?");
		$checklogin->bind_param("s", $email_address);

		$email_address = $login_values['email_address'];//get input value
		$checklogin->execute(); //execute the query

		$result = $checklogin->get_result(); // get the mysqli result
		$user = $result->fetch_assoc(); // fetch data   

		if(!empty($user))
		{
			$enteredpassword = $login_values['password'];

			// decrypt the password
			$realpassword = base64_decode(base64_decode($user['password']));

			if($enteredpassword == $realpassword) //check given password was same or not
			{
				require '../vendor/autoload.php';

				$redis = new Predis\Client(); //create an instance
				
				$user = ([
					"email_address"=>$email_address,
					'id'=>$user['id']
				]);
				// Set values to redis
				$redis->set("users", json_encode($user));
				
				echo json_encode(['status'=>'true','id'=>$user['id']]);
			}
			else
				echo $error_values;
		}
		else
			echo $error_values;
	}
	else
		echo $error_values;
}

?>