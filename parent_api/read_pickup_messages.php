<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."general_messages.php");


$messages = new Messages($conn);

$messages->adm_no = $_GET['adm_no'];


$messages_results = $messages->readPickupMessages();

$results = array();

$num = $messages_results->rowCount();

if ($num > 0) {
	while ($row = $messages_results->fetch(PDO::FETCH_ASSOC)) {

	$results[]=$row;

}
	echo json_encode($results);
}else{
	echo json_encode([]);
}


?>