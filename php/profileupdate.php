<?php
// include constant file
include('constant.php');

// Get input details
$profile_update = $_POST;

/*update login & registration details*/
// Create connection
$dbconnection = new mysqli($servername, $username, $password, $dbname);

if($profile_update['old_password'] !='' && $profile_update['new_password']!='')
{
	$update_login_details = $dbconnection->prepare("UPDATE users SET email_address = ?, password=? where id = ?");
	$update_login_details->bind_param("ssi", $email_address, $password, $profile_update['id']);
}
else
{
	$update_login_details = $dbconnection->prepare("UPDATE users SET email_address = ? where id = ?");
	$update_login_details->bind_param("si", $email_address, $profile_update['id']);
}

// set parameters and execute
$email_address = $profile_update['email_address'];
$password = base64_encode(base64_encode($profile_update['new_password']));
$update_result = $update_login_details->execute();

/*Update profile details*/
// Manager Class

require '../vendor/autoload.php';
$database= new MongoDB\Client("mongodb://127.0.0.1/");
$db = $database->users;
$collection = $db->profile;

// Filter the record
$query = array('user_id' => $profile_update['id']);
$document = $collection->findOne($query);

if($document)
{
	$updateResult = $collection->updateOne(
	    [ 'user_id' => $profile_update['id'] ],
	    [ '$set' => [ 'mobile_number'=> $profile_update['mobile_no'], 'dob' => $profile_update['dob'], 'age' => $profile_update['age'], 'first_name' => $profile_update['first_name'], 'last_name' => $profile_update['last_name'] ]]
	);
}
else
{
	$insertOneResult = $collection->insertOne([
		'user_id' => $profile_update['id'],
		'mobile_number'=>$profile_update['mobile_no'],
		'first_name' => $profile_update['first_name'],
		'last_name'=>$profile_update['last_name'],
		'dob' => $profile_update['dob'],
		'age' => $profile_update['age'],
	]);
}

echo true;

?>