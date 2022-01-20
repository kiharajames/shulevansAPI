<?php
//headers
// header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');
// header('Access-Control-Allow-Methods: POST');
// header('Access-Control-Allow-Methods: GET');
// header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');

// // $data  = $_POST['data'];
// // $data  = $_GET['data'];
// // echo (json_encode($data));
// $message = $_POST['message'];
// $mode = $_POST['mode'];
// $phone = $_POST['phone'];
// $data = $message." | ".$mode." | ".$phone;

// date_default_timezone_set('Africa/Nairobi');

// $time = date('h:i:s');

// $logFile = "mydata.txt";
// $log = fopen($logFile, "w");

// $data = $data."|".$time;
// fwrite($log, $data);
// fclose($log);
?>

<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');

//get the time
date_default_timezone_set('Africa/Nairobi');
$date = date('Y-m-d ');
$time= date('h:i:s');

$messageType = 'driver-parent';
$message = $_POST['message'];
$phone = $_POST['phone'];
$mode = $_POST['mode'];
//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."drivers.php");
require_once(CORE_PATH.DS."parent_tokens.php");
// require_once(CORE_PATH.DS."message_settings.php");
require_once(CORE_PATH.DS."notifications.php");
require_once(CORE_PATH.DS."general_messages.php");

//instantiate the classes
$driver = new Drivers($conn);
$notifications = new MobileNotifications();
$parent_tokens = new ParentTokens($conn);
$general_messages = new Messages($conn);

//Use these with postman
// $data  = json_decode(file_get_contents("php://input"));
// $driver->phone = $data->driver_phone;
// $student->adm_no = $data->adm_no;
// $driver->adm_no = $data->adm_no;//for recording the pickup event
// $driver->mode = $data->mode;


$driver->phone = $phone;

$driver_result = $driver->read_single();
$driver_row = $driver_result->fetch(PDO::FETCH_ASSOC);

$bus = $driver_row['bus'];
$general_messages->bus = $bus;
$general_messages->messageType = $messageType;
$general_messages->date_sent = $date;
$general_messages->time_sent = $time;

if ($mode == "pickup") {
    $messaging_result = $general_messages->readParentsPhoneByPickupBus();
}else{
    $messaging_result = $general_messages->readParentsPhoneByDroppingBus();
}


while ($messaging_row = $messaging_result->fetch(PDO::FETCH_ASSOC)) {
    $parent_phone = $messaging_row['phone'];
    $kidsFirstName = $messaging_row['first_name'];
    $adm_no = $messaging_row['adm_no'];
    $school = $messaging_row['school'];
    //prepare for mobile notifications sending
    $parent_tokens->phone = $parent_phone;
    //get the token
    $token_result = $parent_tokens->readToken();
    $token_row = $token_result->fetch(PDO::FETCH_ASSOC);
    $token = $token_row['token'];

    //send a mobile notification to the parent app
    $notifications->title ="A message for ".$kidsFirstName;
    $notifications->body = $message;
    $notifications->token = $token;

    //record the message in the db
    $general_messages->message = $message;
    $general_messages->recipient = $adm_no;
    $general_messages->school = $school;
    $general_messages->recordMessage();

    //send the mobile notification
    $notifications->sendNotification();

}

?>