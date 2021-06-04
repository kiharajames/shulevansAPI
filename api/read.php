<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initalize the API
include_once "../core/initialize.php";
//instantiate the post class
$post = new Post($conn);
$result = $post->read();
//get the row count
$num = $result->rowCount();

if ($num > 0) {
	$post_arr = array();
	
	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		extract($row);
		$post_item = array(
			'id' => $id,
			'title'=>$title,
			'body' => html_entity_decode($body),
			'author' => $author,
			'category_id' => $category_id,
			'category_name' => $category_name,

		);
		$post_arr[]= $post_item;
	}
	//convert to json ansd output the result
	echo json_encode($post_arr);
	
}else{
	echo json_encode(array('message' => 'No posts were found'));
	
 }

?>