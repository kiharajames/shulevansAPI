<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."pickup_history.php");
require_once(CORE_PATH.DS."parents.php");


$parent = new Parents($conn);

$parent->phone = $_GET['parent_phone'];


//get the parents bus
$parent_result = $parent->readParent();
$parent_row = $parent_result->fetch(PDO::FETCH_ASSOC);

$parent_data = array();
$parent_data[] = $parent_row;


$num = $parent_result->rowCount();

if ($num > 0) {

	echo json_encode($parent_data);
}else{
	echo json_encode("error");
}


?>