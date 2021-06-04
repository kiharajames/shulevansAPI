<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');
//initalize the API
include_once "../core/initialize.php";

//instantiate the post class
$post = new Post($conn);

//get the posted data
$data  = json_decode(file_get_contents("php://input"));
$post->title = $data->title;
$post->body = $data->body;
$post->author = $data->author;
$post->category_id = $data->category_id;

//create theh post
if ($post->create()) {
	echo json_encode(array('message'=>'The post was created'));
}else{
	echo json_encode(array('message'=>'The post was not created'));
}

?>