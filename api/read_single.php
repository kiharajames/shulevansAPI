<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initalize the API
include_once "../core/initialize.php";

//instantiate the post class
$post = new Post($conn);

$post->id = isset($_GET['id']) ? $_GET['id'] : die("");

$result = $post->read_single();

$post_arr = array(
	'id' => $post->id,
	'title' => $post->title,
	'body' => $post->body,
	'author' => $post->author,
	'category_id' => $post->category_id,
	'category_name' => $post->category_name,
	);
echo json_encode($post_arr);


?>