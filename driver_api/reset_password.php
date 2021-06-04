<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');
//Establishing connection with server by passing "server_name", "user_id", "password".
//initalize the API
include_once "../core/initialize.php";

require_once(INC_PATH.DS."config.php");
require_once(CORE_PATH.DS."AfricasTalkingGateway.php");
require_once(CORE_PATH.DS."drivers.php");
require_once(INC_PATH.DS."includes.php");

// Set your app credentials
$username   = "shulevans";
$apiKey     = "6088b2125dc15335c540e631a010bd42e3f0f6cec37a749129b88b343364a2c9";

// Initialize the SDK
$gateway         = new AfricasTalkingGateway($username, $apiKey);
// Get the SMS service
// Set the numbers you want to send to in international format

//get the audience to send the message to
// $data = json_decode(file_get_contents("php://input"));
// $phone = $data->phone;
$phone=$_POST['phone'];

$recipients = "+254".$phone;

$pass = get_rand_alphanumeric(8);
$message = 'Dear driver, use this password to log in to your account: '.$pass.'. You may change it after you log in.';
// to enable us to serve you better. Pay conveniently to paybill number 4042561 Account number (Enter the name of your child). For any inquiries contact us through 0722714028 or 0797499554. FOXX TOURS(Official transport provider, '.$school_name.')'
//send the message to each particular student';
$from = "SHULEVANS";

//driver class
$driver = new Drivers($conn);
$driver->phone = $phone;
//check whether driver exists
$driver_read_results = $driver->read_single();

$num = $driver_read_results->rowCount();

if ($num>0) {
    $driver->newPass = md5($pass);
    $driver_change_pass_results = $driver->changePassword();

    $change_num = $driver_change_pass_results->rowCount();
    if ($change_num>0) {
        try {
         $results = $gateway->sendMessage($recipients, $message, $from);
         echo json_encode([array('code' => "1" )]);
            
    }catch (AfricasTalkingGatewayException $e) {
            echo "Encountered an error while sending: " . $e->getMessage();
            echo json_encode([array('code' => "2" )]);
        }
    }else{
        echo json_encode([array('code' => "2" )]);
    }
}else{
    echo json_encode([array('code' => "0" )]);
}


?>
