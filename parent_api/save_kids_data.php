<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."student.php");

$student = new Student($conn);

// $data = json_decode(file_get_contents('php://input'));
// $student->adm_no = $data->adm_no;
// $student->first_name = $data->first_name;
// $student->last_name = $data->last_name;
// $student->gender = $data->gender;
// $student->class = $data->class;

$student->adm_no = $_POST['adm_no'];
$student->first_name = $_POST['first_name'];
$student->last_name = $_POST['last_name'];
$student->gender = $_POST['gender'];
$student->class = $_POST['class'];

//update the data
$student_result = $student->updateProfile();

$num = $student_result->rowCount();
if ($num > 0) {
	echo json_encode("1");
}else{
	echo json_encode("error");
}

?>