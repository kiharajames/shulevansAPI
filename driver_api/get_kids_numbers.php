<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."pickup_history.php");
require_once(CORE_PATH.DS."drivers.php");
require_once(CORE_PATH.DS."student.php");


$driver = new Drivers($conn);
$student = new Student($conn);

$driver->phone = $_GET['driver_phone'];


//get the drivers bus
$driver_result = $driver->read_single();
$driver_row = $driver_result->fetch(PDO::FETCH_ASSOC);

$driver_bus = $driver_row['bus'];

$student->bus = $driver_bus;
$results = $student->selectKidsByBus();

$kids_data = array();

$num = $results->rowCount();

if ($num > 0) {
	while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
	$kids_data[] = $row;
}
echo json_encode($kids_data);

}else{
	echo json_encode("error");
}


?>