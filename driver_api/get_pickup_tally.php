<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."pickup_history.php");
require_once(CORE_PATH.DS."drivers.php");


$pickup_history = new PickupHistory($conn);
$driver = new Drivers($conn);

$driver->phone = $_GET['driver_phone'];


//get the drivers bus
$driver_result = $driver->read_single();
$driver_row = $driver_result->fetch(PDO::FETCH_ASSOC);
$pickup_history->mybus = $driver_row['bus'];

$tallyResults = array();


$tallyPickupHomeResults = $pickup_history->getTallyPickupHome();
$num = $tallyPickupHomeResults->rowCount();
if ($num > 0) {
	$tallyPickupHomeRow = $tallyPickupHomeResults->fetch(PDO::FETCH_ASSOC);
	$tallyResults[] = array('a' => $tallyPickupHomeRow['count'] );
}else{
	$tallyResults[] = array('a' => '0' );
}


$tallyDroppedSchlResults = $pickup_history->getTallyDroppedSchl();
$num1 = $tallyDroppedSchlResults->rowCount();
if ($num1 > 0) {
	$tallyDroppedSchlRow = $tallyDroppedSchlResults->fetch(PDO::FETCH_ASSOC);
	$tallyResults[] = array('b' => $tallyDroppedSchlRow['count']);
}else{
	$tallyResults[] = array('b' => '0' );
}


$tallyDroppedHmResults = $pickup_history->getTallyDroppedHm();
$num2 = $tallyDroppedHmResults->rowCount();
if ($num2 > 0) {
	$tallyDroppedHmRow = $tallyDroppedHmResults->fetch(PDO::FETCH_ASSOC);
	$tallyResults[] = array('c' => $tallyDroppedHmRow['count']);
}else{
	$tallyResults[] = array('c' => '0');
}


$getTallyPickedSchlResults = $pickup_history->getTallyPickedSchl();
$num3 = $getTallyPickedSchlResults->rowCount();
if ($num3 > 0) {
	$getTallyPickedSchlRow = $getTallyPickedSchlResults->fetch(PDO::FETCH_ASSOC);
	$tallyResults[] = array('d' => $getTallyPickedSchlRow['count']);
}else{
	$tallyResults[] = array('d' => '0');
}

echo json_encode($tallyResults);

?>