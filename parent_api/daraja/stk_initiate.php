<?php
  include 'access_token.php';
  date_default_timezone_set('Africa/Nairobi');


  # define the variales
  # provide the following details, this part is found on your test credentials on the developer account
  $BusinessShortCode = '4041987';
  $Passkey = '969b9f3a4ffe8a365659f28c28745ba450892c5e9b30ac9b0addc9fbc0efbc8e';  
  
  /*
    This are your info, for
    $PartyA should be the ACTUAL clients phone number or your phone number, format 2547********
    $AccountRefference, it maybe invoice number, account number etc on production systems, but for test just put anything
    TransactionDesc can be anything, probably a better description of or the transaction
    $Amount this is the total invoiced amount, Any amount here will be 
    actually deducted from a clients side/your test phone number once the PIN has been entered to authorize the transaction. 
    for developer/test accounts, this money will be reversed automatically by midnight.
  */
  $phone = $_POST['phone'];
  $PartyA = "254".$phone; // This is your phone number, 
  $AccountReference = $_POST['school_name'];
  $TransactionDesc = 'Fees payment';
  $Amount = $_POST['amount'];
  $school = $_POST['school'];
  $adm_no = $_POST['adm_no'];

  // $data = json_decode(file_get_contents("php://input"));
  // $phone = $data->phone;
  // $PartyA = "254".$phone; // This is your phone number, 
  // $AccountReference = $data->school_name;
  // $TransactionDesc = 'Fees payment';
  // $Amount = $data->amount;
  // $school = $data->school;
  // $adm_no = $data->adm_no;
  
  # Get the timestamp, format YYYYmmddhms -> 20181004151020
  $Timestamp = date('YmdHis');    
  
  # Get the base64 encoded string -> $password. The passkey is the M-PESA Public Key
  $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);


  # M-PESA endpoint urls
  
  $initiate_url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

  # callback url
  $CallBackURL = 'https://shulevans.com/shulevansAPI/parent_api/daraja/callback_url.php?adm_no='.$adm_no.'&school='.$school.'&phone='.$PartyA.'';

  # header for stk push
  $stkheader = array('Content-Type:application/json','Authorization:Bearer '.$access_token);

  # initiating the transaction
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $initiate_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); //setting custom header

  $curl_post_data = array(
    //Fill in the request parameters with valid values
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $Amount,
    'PartyA' => $PartyA,
    'PartyB' => $BusinessShortCode,
    'PhoneNumber' => $PartyA,
    'CallBackURL' => $CallBackURL,
    'AccountReference' => $AccountReference,
    'TransactionDesc' => $TransactionDesc
  );

  $data_string = json_encode($curl_post_data);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
  $curl_response = curl_exec($curl);
  
  $response = json_decode($curl_response, true);
  //check whether the tranaction has been sent successfully or we have an error somewhere.
  if (isset($response['errorMessage'])) {
    echo json_encode(0);
  }else{
    echo json_encode(1);
  }
?>