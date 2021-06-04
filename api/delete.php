<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');
//initalize the API
include_once "../core/initialize.php";

//instantiate the post class
$post = new Post($conn);

//get the posted data
$data = json_decode(file_get_contents("php://input"));
$post->id = $data->id;

//create the post
if ($post->delete()) {
	echo json_encode(array('message'=>'The data was deleted'));
}else{
	echo json_encode(array('message'=>'The data was not deleted'));
}
?>