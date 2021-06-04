<?php
//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."student.php");
require_once(CORE_PATH.DS."drivers.php");


$student = new Student($conn);
$driver = new Drivers($conn);

$student->adm_no = $_GET['adm_no'];

$results = $student->readSingle();

$drivers_data = array();

$num = $results->rowCount();

if ($num > 0) {
	$row = $results->fetch(PDO::FETCH_ASSOC);

	$pickup = $row['pickup'];

	if ($pickup == "both") {
		$pickup_vehicle = $row['pickup_vehicle'];
		$driver->bus = $pickup_vehicle;
		//get the drivers data
		$driver_pickup_results = $driver->readDriverByVehicle();
		while ($driver_pickup_row = $driver_pickup_results->fetch(PDO::FETCH_ASSOC)) {
			$drivers_data[]=$driver_pickup_row;
		}

		$dropping_vehicle = $row['dropping_vehicle'];
		$driver->bus = $pickup_vehicle;
		$driver_dropping_results = $driver->readDriverByVehicle();
		while ($driver_dropping_row = $driver_pickup_results->fetch(PDO::FETCH_ASSOC)) {
			$drivers_data[] = $driver_dropping_row;
		}

	}elseif ($pickup == "pickup") {
		$pickup_vehicle = $row['pickup_vehicle'];
		$driver->bus = $pickup_vehicle;
		//get the drivers data
		$driver_pickup_results = $driver->readDriverByVehicle();
		while ($driver_pickup_row = $driver_pickup_results->fetch(PDO::FETCH_ASSOC)) {
			$drivers_data[]=$driver_pickup_row;
		}
	}elseif ($pickup == "dropping") {
		$dropping_vehicle = $row['dropping_vehicle'];
		$driver->bus = $pickup_vehicle;
		$driver_dropping_results = $driver->readDriverByVehicle();
		while ($driver_dropping_row = $driver_pickup_results->fetch(PDO::FETCH_ASSOC)) {
			$drivers_data[] = $driver_dropping_row;
		}
		
	}
	

	echo json_encode($drivers_data);
}else{
	echo json_encode("0");
}

?>