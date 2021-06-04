<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initalize the API
include_once "../core/initialize.php";
require_once(CORE_PATH.DS."parents.php");
require_once(CORE_PATH.DS."drivers.php");

// $data = json_decode(file_get_contents("php://input"));
// $role = $data->role; 
$role = $_POST['role']; 
if ($role == 'parent') {
	//instantiate the parent class
	$parent = new Parents($conn);

	// $parent->phone = $data->username;
	// $parent->login = $data->login;

	$parent->phone = $_POST['username'];
	$parent->login = $_POST['login'];

	$parent->login = md5($parent->login);

	$result = $parent->authenticate();
	$num = $result->rowCount();

	if ($num > 0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$parent->phone_check = $row['phone'];
		$parent->password_check = $row['login'];
		$first_name =$row['first_name'];

		$response_array = array();

		if ($parent->phone == $parent->phone_check && $parent->login == $parent->password_check) {
			$response_array[] = array('code' => "1", "first_name" => $first_name);
			echo json_encode($response_array);
		}
	}else{
		echo json_encode([array('code' => "0", "first_name" => "")]);
	}

}elseif ($role == 'driver') {
	$driver = new Drivers($conn);

	// $driver->phone = $data->username;
	// $driver->login = $data->login;

	$driver->phone = $_POST['username'];
	$driver->login = $_POST['login'];

	$driver->login = md5($driver->login);

	$result = $driver->authenticate();
	$num = $result->rowCount();

	if ($num > 0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$driver->phone_check = $row['phone'];
		$driver->password_check = $row['login'];
		$first_name =$row['first_name'];

		if ($driver->phone == $driver->phone_check && $driver->login == $driver->password_check) {
			echo json_encode([array('code' => "1", "first_name" => $first_name)]);
		}
	}else{
		echo json_encode([array('code' => "0", "first_name" => "")]);
	}

}

?>