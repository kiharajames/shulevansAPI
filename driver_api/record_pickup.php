<?php
//headers
// header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');
// header('Access-Control-Allow-Methods: POST');
// header('Access-Control-Allow-Methods: GET');
// header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');

// $data  = $_POST['data'];
// $data  = $_GET['data'];
//echo (json_encode($data));

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

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."AfricasTalkingGateway.php");

require_once(CORE_PATH.DS."student.php");
require_once(CORE_PATH.DS."drivers.php");
require_once(CORE_PATH.DS."parent_tokens.php");
require_once(CORE_PATH.DS."school.php");
// require_once(CORE_PATH.DS."message_settings.php");
require_once(CORE_PATH.DS."notifications.php");

//instantiate the classes
$student = new Student($conn);
$driver = new Drivers($conn);
$school = new School($conn);
// $message_settings = new MessageSettings($conn);
$notifications = new MobileNotifications();
$parent_tokens = new ParentTokens($conn);

//Use these with postman
// $data  = json_decode(file_get_contents("php://input"));
// $driver->phone = $data->driver_phone;
// $student->adm_no = $data->adm_no;
// $driver->adm_no = $data->adm_no;//for recording the pickup event
// $driver->mode = $data->mode;

//get the posted data
$data  = $_GET['data'];
$data = explode(":", $data);

$driver->bus = $data[0];
$student->adm_no = $data[2];
$driver->adm_no = $data[2];//for recording the pickup event
$mode = $data[1];

$status = "";
$message = "";


if ($mode == "ds") {
	$driver->mode = "dropped at school";
    $status = "dropped at school";
}
if ($mode == "ps") {
	$driver->mode = "left school";
    $status = "left school";
}
if ($mode == "dh") {
	$driver->mode = "dropped at home";
    $status = "dropped at home";
}
if ($mode == "ph") {
	$driver->mode = "picked from home";
    $status = "picked from home";
}

// $driver->phone = $_POST['driver_phone'];
// $student->adm_no = $_POST['adm_no'];
// $driver->adm_no = $_POST['adm_no'];//for recording the pickup event
// $driver->mode = $_POST['mode'];

$driver->date_recorded = $date;
$driver->time_recorded = $time;
//get the results of the queries

$student_result = $student->readSingle();

//get the students point, school, and phone
$student_row = $student_result->fetch(PDO::FETCH_ASSOC);
$student_school = $student_row['school'];
$pickuppoint = $student_row['pickuppoint'];
$sd_phone = $student_row['phone'];
$sd_first_name = $student_row['first_name'];


//pass the school to the drivers class
$driver->school = $student_school;

//pass the pickuppoint to the drivers class
$driver->pickuppoint = $pickuppoint;


//prepare for mobile notifications sending
$parent_tokens->phone = $sd_phone;
//get the token
$token_result = $parent_tokens->readToken();
$token_row = $token_result->fetch(PDO::FETCH_ASSOC);
$token = $token_row['token'];


//for SMS sending
// Set your api credentials
$username   = "shulevans";
$apiKey     = "6088b2125dc15335c540e631a010bd42e3f0f6cec37a749129b88b343364a2c9";

// Initialize the SDK
$gateway         = new AfricasTalkingGateway($username, $apiKey);

//get the school name to be used in the message sent.
$school->school_id = $student_school;
$school_results = $school->read_school();

$school_row = $school_results->fetch(PDO::FETCH_ASSOC);

$school_name = $school_row['name'];

//get the messages set
// $message_results = $message_settings->getMessage();
// $message_row = $message_results->fetch(PDO::FETCH_ASSOC);\6

// $pick_up_message = $message_row['pick_up_message'];
// $arrival_school_message = $message_row['arrival_school_message'];
// $departure_message = $message_row['departure_message'];
// $drop_off_message = $message_row['drop_off_message'];

//get the message to be sent according to the even(status) of that time
$extra_message = " To give us feedback SMS 0797499554.";


if ($status=="dropped at school") {
        $message = 'Your child has been dropped off at '.$school_name.' Time: '.$time.' on '.$date.', thank you.'. $extra_message;
        $sdbus=$student_row ['pickup_vehicle'];
    }elseif ($status=="left school") {
        $message = 'Your child has departed '.$school_name.' Time: '.$time.' on '.$date.', thank you.'. $extra_message;
        $sdbus=$student_row['dropping_vehicle'];
    }elseif ($status=="dropped at home") {
        $message = 'Your child has been dropped at home at '.$pickuppoint.' Time: '.$time.' on '.$date.', thank you.'. $extra_message;
        $sdbus=$student_row['dropping_vehicle'];
    }elseif ($status=="picked from home") {
        $message = 'Your child has been picked from home at '.$pickuppoint.' Time: '.$time.' on '.$date.', thank you.'. $extra_message;
        $sdbus=$student_row['pickup_vehicle'];
    }

    
    $recipients = "+254".$sd_phone;

//enter the data
if($driver->record_pickup()){
    //send a mobile notification to the parent app
    $notifications->title = $sd_first_name." ".$status;
    $notifications->body = $message;
    $notifications->token = $token;

    

    //record the message in the db
    $driver->message = $message;
    $driver->recordMessage();

    //send the mobile notification
    $notifications->sendNotification();

    $from = "SHULEVANS";
    //     if ($sdbus == "KAE 667M" || $sdbus == "KAE-667M") {
    //         try {
    //         // Thats it, hit send and we'll take care of the rest.
    //      $results = $gateway->sendMessage($recipients, $message, $from);
    //      echo json_encode("1");
            
    // }catch (AfricasTalkingGatewayException $e) {
    //         echo "Encountered an error while sending: " . $e->getMessage();

    //     }   
    //     }else{
    //         echo json_encode("1-1");
    //     }

}else{
    echo json_encode("Error");
}


?>