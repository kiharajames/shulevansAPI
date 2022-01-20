
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
require_once(CORE_PATH.DS."message_settings.php");
require_once(CORE_PATH.DS."notifications.php");


//instantiate the classes
$student = new Student($conn);
$driver = new Drivers($conn);
$school = new School($conn);
$message_settings = new MessageSettings($conn);
$notifications = new MobileNotifications();
$parent_tokens = new ParentTokens($conn);

//Use these with postman
// $data  = json_decode(file_get_contents("php://input"));
// $driver->phone = $data->driver_phone;
// $student->adm_no = $data->adm_no;
// $driver->adm_no = $data->adm_no;//for recording the pickup event
// $driver->mode = $data->mode;

//get the posted data
$driver->phone = $_POST['driver_phone'];
$student->adm_no = $_POST['adm_no'];
$driver->adm_no = $_POST['adm_no'];//for recording the pickup event
$driver->mode = $_POST['mode'];

$driver->date_recorded = $date;
$driver->time_recorded = $time;
//get the results of the queries
$driver_result = $driver->read_single();
$student_result = $student->readSingle();

//get the drivers bus
$driver_row = $driver_result->fetch(PDO::FETCH_ASSOC);
$driver->bus = $driver_row['bus'];

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
$message_results = $message_settings->getMessage();
$message_row = $message_results->fetch(PDO::FETCH_ASSOC);

$pick_up_message = $message_row['pick_up_message'];
$arrival_school_message = $message_row['arrival_school_message'];
$departure_message = $message_row['departure_message'];
$drop_off_message = $message_row['drop_off_message'];

//get the message to be sent according to the even(status) of that time
$extra_message = " To give us feedback SMS 0797499554.";
$mode = $driver->mode;

if ($mode=="dropped at school") {
        $message = $arrival_school_message.' at '.$school_name.' Time: '.$time.' on '.$date.', thank you.'. $extra_message;
        $sdbus=$student_row ['pickup_vehicle'];
    }elseif ($mode=="left school") {
        $message = $departure_message.' at '.$school_name.' Time: '.$time.' on '.$date.', thank you.'. $extra_message;
        $sdbus=$student_row['dropping_vehicle'];
    }elseif ($mode=="dropped at home") {
        $message = $drop_off_message.' at '.$pickuppoint.' Time: '.$time.' on '.$date.', thank you.'. $extra_message;
        $sdbus=$student_row['dropping_vehicle'];
    }elseif ($mode=="picked from home") {
        $message = $pick_up_message.' at '.$pickuppoint.' Time: '.$time.' on '.$date.', thank you.'. $extra_message;
        $sdbus=$student_row['pickup_vehicle'];
    }

    
    $recipients = "+254".$sd_phone;

//enter the data
if($driver->record_pickup()){
    //send a mobile notification to the parent app
    $notifications->title = $sd_first_name." ".$mode;
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