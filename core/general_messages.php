<?php

class Messages{
	private $conn;
	private $table = "general_messaging";
	public $date_sent;
	public $time_sent;
	public $recipient;
	public $messageType;
	public $message;
	public $bus;
	public $school;

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

	//read parents numbers for kids in the picking bus
	public function readParentsPhoneByPickupBus(){
		$query = "SELECT * FROM users WHERE pickup_vehicle=:bus";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':bus', $this->bus);
		$stmt->execute();

		return $stmt;
	}

	//read parents numbers for kids in the dropping bus
	public function readParentsPhoneByDroppingBus(){
		$query = "SELECT * FROM users WHERE dropping_vehicle=:bus";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':bus', $this->bus);
		$stmt->execute();

		return $stmt;
	}

	public function recordMessage(){
		$query = "INSERT INTO general_messaging(date_sent, time_sent, recipient, message_type, message, school) VALUES (:date_sent, :time_sent, :recipient,:message_type, :message, :school)"; //Insert query
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam('date_sent', $this->date_sent);
		$stmt->bindParam('time_sent', $this->time_sent);
		$stmt->bindParam('recipient', $this->recipient);
		$stmt->bindParam('message_type', $this->messageType);
		$stmt->bindParam('message', $this->message);
		$stmt->bindParam('school', $this->school);

		$stmt->execute();
		return $stmt;

	}
}

?>