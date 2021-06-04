<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."app_version.php");


$app_version = new AppVersion($conn);
$app_version->app = $_GET['app'];

//get the drivers bus
$version_result = $app_version->checkVersion();
$version_row = $version_result->fetch(PDO::FETCH_ASSOC);




$num = $version_result->rowCount();

if ($num > 0) {
	$version = $version_row['version'];
	echo json_encode($version);
}else{
	echo json_encode("error");
}


?>