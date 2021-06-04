<?php



	# 1.1. Config Section
		$dbName = 'shulevan_foxx-tours-v1';
		$dbHost = 'localhost';
		$dbUser = 'shulevan_begress';
		$dbPass = 'Begress123#';

	# 1.1.1 establish a connection
	try{
		$conn = new PDO("mysql:dbhost=$dbHost;dbname=$dbName", $dbUser, $dbPass);
		echo "Connection was successful";
	}
	catch(PDOException $e){
		die("Error Connecting ".$e->getMessage());
	}

	
?>