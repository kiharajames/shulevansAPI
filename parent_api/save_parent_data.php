<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."parents.php");

$parent = new Parents($conn);

// $data = json_decode(file_get_contents('php://input'));
// $parent->phone = $data->parent_phone;
// $parent->newPhone = $data->newPhone;

$parent->phone = $_POST['parent_phone'];
$parent->newPhone = $_POST['newPhone'];
$parent->first_name = $_POST['first_name'];
$parent->last_name = $_POST['last_name'];
$parent->gender = $_POST['gender'];
$parent->email = $_POST['email'];

//uodate the data
$parent_result = $parent->updateData();
$num = $parent_result->rowCount();
if ($num > 0) {
	echo json_encode("1");
}else{
	echo json_encode("error");
}

?>