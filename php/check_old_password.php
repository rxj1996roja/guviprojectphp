<?php
// include constant file
include('constant.php');

// Get email address
$check_exists = $_POST;

if(!empty($check_exists)) //check input values are not empty
{
	// if not empty check then check given email address already exists or not

	// Create connection
	$dbconnection = new mysqli($servername, $username, $password,$dbname);

	$passwordmatched = $dbconnection->prepare("SELECT password FROM `users` WHERE id = ?");
	$passwordmatched->bind_param("i", $id);

	$id = $check_exists['id'];//get input value
	$passwordmatched->execute(); //execute the query

	$result = $passwordmatched->get_result(); // get the mysqli result
	$user = $result->fetch_assoc(); // fetch data   
	if(!empty($user))
	{
		$enteredpassword = $check_exists['old_password'];
		// decrypt the password
		$realpassword = base64_decode(base64_decode($user['password']));
		if($enteredpassword == $realpassword) //check given password was same or not
			echo 'true';
		else
			echo 'false';
	}
	else
		echo 'false';	
}

?>