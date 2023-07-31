<?php
// include constant file
include('constant.php');

$user['oid'] = '';

// Get input details
$user_login_details = $_GET;
if(!empty($user_login_details)) //check input values are not empty
{
	// if not empty check given details are valid or not
	// Create connection
	$dbconnection = new mysqli($servername, $username, $password, $dbname);

	$getrecords = $dbconnection->prepare("SELECT email_address FROM `users` WHERE id = ?");
	$getrecords->bind_param("i", $id);

	$id = $user_login_details['id'];//get input value
	$getrecords->execute(); //execute the query

	$result = $getrecords->get_result(); // get the mysqli result
	$user = $result->fetch_assoc(); // fetch data   

	require '../vendor/autoload.php';
	$database= new MongoDB\Client("mongodb://127.0.0.1/");
	$db = $database->users;
	$collection = $db->profile;

	// Filter the record
	$query = array('user_id' => $id);
	$document = $collection->findOne($query);

	if($document)
	{
	    $user['oid'] = isset($document['_id'])?$document['_id']:'';
	    $user['first_name'] = isset($document['first_name'])?$document['first_name']:'';
	    $user['last_name'] = isset($document['last_name'])?$document['last_name']:'';
	    $user['mobile_number'] = isset($document['mobile_number'])?$document['mobile_number']:'';
	    $user['age'] = isset($document['age'])?$document['age']:'';
	    $user['dob'] = isset($document['dob'])?$document['dob']:'';
	}

	if(!empty($user))
		echo json_encode(['status'=>true,'data'=>$user]);
	else
		echo json_encode(['status'=>false,'id'=>null]);
}

?>