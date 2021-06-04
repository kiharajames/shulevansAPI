<?php
	//headers
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	header('Access-Control-Allow-Methods: POST');
	header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');

	//initalize the API
	include_once "../../core/initialize.php";

	require_once(CORE_PATH.DS."payments.php");
	require_once(CORE_PATH.DS."student.php");
	require_once(CORE_PATH.DS."accounting_period.php");

	$payments = new Payments($conn);
	$student = new Student($conn);
	$accounting_period = new AccountingPeriod($conn);

	date_default_timezone_set("Africa/Nairobi");
	$stkCallbackResponse = file_get_contents('php://input');
	$logFile = "stkPushCallbackResponse.json";
	$log = fopen($logFile, "a");
	$adm_no = $_GET['adm_no'];
	$school = $_GET['school'];
	fwrite($log, $stkCallbackResponse);
	fwrite($log, $adm_no);
	fwrite($log, $school);
	fclose($log);

	$resultjson = json_decode($stkCallbackResponse, true);

	$payment_date = date('Y-m-d');
	$payment_time = date('H:i:s');
	$payment_gross = $resultjson['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
	$txn_id = $resultjson['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
	$payment_status = "Completed";
	$school = $_GET['school'];
	$phone = $_GET['phone'];

	//get the students name
	$student->adm_no = $adm_no;
	$results = $student->readSingle();
	$row = $results->fetch(PDO::FETCH_ASSOC);
	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
	$full_name = $first_name. " ".$last_name;

	//get the accounting period, i.e the term and the year
	$accounting_period->school = $school;
	$results = $accounting_period->readPeriod();
	$num = $results->rowCount();
	if ($num>0) {
		$row = $results->fetch(PDO::FETCH_ASSOC);
		$term = $row['term'];
		$year = $row['year'];
	}else{
		$term = "term";
		$year = "year";
	}


	//prepare dta to record to the db
	$payments->payment_date = date('Y-m-d');
	$payments->payment_time = date('H:i:s');
	$payments->payment_gross = $resultjson['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
	$payments->adm_no = $_GET['adm_no'];
	$payments->txn_id = $resultjson['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
	$payments->payment_status = "Completed";
	$payments->school = $_GET['school'];
	$payments->term = $term;
	$payments->year = $year;

	# 1.1.2 Insert Response to Database
	try{

		$result = $payments->recordPayment();
		$num = $result->rowCount();
		
		if ($num > 0) {
			$student->payment_gross = $payment_gross;
			$student->adm_no = $adm_no;
			$student->school = $school;

			$result = $student->updateBalance();
			$num = $result->rowCount();
			if ($num > 0) {
				require_once(CORE_PATH.DS."AfricasTalkingGateway.php");
				// Set your api credentials
				$username   = "shulevans";
				$apiKey     = "6088b2125dc15335c540e631a010bd42e3f0f6cec37a749129b88b343364a2c9";

				// Initialize the SDK
				$gateway = new AfricasTalkingGateway($username, $apiKey);
				$from = "SHULEVANS";
				$recipients = $phone;
				$message = "Dear parent, this is to notify you that a payment of Kshs ".$payment_gross." has been completed for ".$full_name.". Thank you.";
				try {
		            // Thats it, hit send and we'll take care of the rest.
					$results = $gateway->sendMessage($recipients, $message, $from);
				
		            
		    	}catch (AfricasTalkingGatewayException $e) {
		            echo "Encountered an error while sending: " . $e->getMessage();

		        }   
			}
		}
	}
	catch(PDOException $e){

		# 1.1.2b Log the error to a file. Optionally, you can set it to send a text message or an email notification during production.
		$errLog = fopen('error.txt', 'a');
		fwrite($errLog, $e->getMessage());
		fclose($errLog);

		# 1.1.2o Optional. Log the failed transaction. Remember, it has only failed to save to your database but M-PESA Transaction itself was successful. 
		$logFailedTransaction = fopen('failedTransaction.txt', 'a');
		fwrite($logFailedTransaction, json_encode($jsonMpesaResponse));
		fclose($logFailedTransaction);
	}

