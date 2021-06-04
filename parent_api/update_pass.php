<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."parents.php");
require_once(CORE_PATH.DS."password_edit.php");


$parent = new Parents($conn);
$password_edit = new PasswordEdits($conn);
$data = json_decode(file_get_contents('php://input'));
$parent->phone = $data->parent_phone;
$password_edit->phone = $data->parent_phone;
$parent->currentPass = $data->currentPass;
$newPass = $data->newPass;
// $password_edit->phone = $_POST['parent_phone'];

// $parent->phone = $_POST['parent_phone'];
// $parent->currentPass = $_POST['currentPass'];
// $newPass = $_POST['newPass'];


$currentPass = $parent->currentPass;
$currentPass = md5($currentPass);

//get the pass saved in the db
$parent_result = $parent->readParent();

$num = $parent_result->rowCount();
if ($num>0) {
	$parent_row = $parent_result->fetch(PDO::FETCH_ASSOC);

	$savedPass = $parent_row['login'];

	//compare the pass entered and the saved pass
	if ($currentPass == $savedPass) {
		$parent->newPass = md5($newPass);

		//change the pass
		$parent_result = $parent->changePassword();

		if ($parent_result == true) {
			$password_edit->updatePass();//record that the password has been edited so that they wont be reminded to edit the password again. 
			echo json_encode("1");
			
		}else{
			echo json_encode("error");
		}

	}else{
		echo json_encode("incorrect");
	}

}else{
	echo json_encode("incorrect");
}



?>