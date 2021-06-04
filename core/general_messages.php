<?php

class Messages{
	private $conn;
	private $table = "general_messaging";
	public $date_sent;
	public $time_sent;
	public $recipient;
	public $messageType;
	public $message;


	public function __construct($conn){
		$this->conn = $conn;
	}

	public function readPickupMessages(){
		$query = "SELECT * FROM general_messaging WHERE recipient=:adm_no AND message_type='picking' ORDER BY id DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':adm_no', $this->adm_no);
		$stmt->execute();

		return $stmt;
	}

	public function readGeneralMessages(){
		$query = "SELECT * FROM general_messaging WHERE recipient=:adm_no AND message_type!='picking' ORDER BY id DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(':adm_no', $this->adm_no);
		$stmt->execute();

		return $stmt;
	}

	public function markPickupsAsRead(){
		$query = "UPDATE general_messaging SET read_status='1' WHERE recipient=:adm_no AND message_type='picking'";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':adm_no', $this->adm_no);
		$stmt->execute();

		return $stmt;
	}

	public function markGeneralAsRead(){
		$query = "UPDATE general_messaging SET read_status='1' WHERE recipient=:adm_no AND message_type!='picking'";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':adm_no', $this->adm_no);
		$stmt->execute();

		return $stmt;
	}
}

?>