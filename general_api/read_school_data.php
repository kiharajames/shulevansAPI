<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."school.php");


$school = new School($conn);

$school->school_id = $_GET['school_id'];


//get the parents bus
$school_result = $school->read_school();
$school_row = $school_result->fetch(PDO::FETCH_ASSOC);

$school_data = array();
$school_data[] = $school_row;


$num = $school_result->rowCount();

if ($num > 0) {
	echo json_encode($school_data);
}else{
	echo json_encode("error");
}


?>