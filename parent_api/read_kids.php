<?php
//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."pickup_history.php");
require_once(CORE_PATH.DS."parents.php");


$parent = new Parents($conn);

$parent->phone = $_GET['parent_phone'];

$results = $parent->getKids();

$kids_data = array();

$num = $results->rowCount();

if ($num>0) {
	while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
		$kids_data[] = $row;
	}
	echo json_encode($kids_data);
}else{
	echo json_encode("0");
}

?>