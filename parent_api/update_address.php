<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."parents.php");
require_once(CORE_PATH.DS."student.php");

$parent = new Parents($conn);
$student = new Student($conn);

// $data = json_decode(file_get_contents("php://input"));
// $parent->address = $data->address;
// $parent->phone = $data->phone;
// $student->address = $data->address;
// $student->phone = $data->phone;

$parent->address = $_POST['address'];
$parent->phone = $_POST['phone'];

$student->address = $_POST['address'];
$student->phone = $_POST['address'];

$no_of_students_results = $parent->getKids();

while ($row = $no_of_students_results->fetch(PDO::FETCH_ASSOC)) {
	$student->adm_no = $row['adm_no'];
	$student->updateLocation();
}
$parent_address_results = $parent->updateLocation();

$num = $parent_address_results->rowCount();

if ($parent_address_results == TRUE) {
	echo json_encode("1");
}else{
	echo json_encode("0");
}

?>