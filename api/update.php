<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');
//initalize the API
include_once "../core/initialize.php";

//instantiate the post class
$post = new Post($conn);

//get the posted data
$data  = json_decode(file_get_contents("php://input"));
$post->id = $data->id;
$post->title = $data->title;
$post->body = $data->body;
$post->author = $data->author;
$post->category_id = $data->category_id;

//create theh post
if ($post->update()) {
	echo json_encode(array('message'=>'the message was Updated'));
}else{
	echo json_encode(array('message'=>'The message was not Updated'));
}
?>