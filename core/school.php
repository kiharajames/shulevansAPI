<?php
Class School{
	public $school_id;
	public $school_name;

	private $conn;
	private $table = "schools";

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function read_school(){
		$query = "SELECT * FROM ".$this->table." WHERE school_id=:school_id";
		$stmt  = $this->conn->prepare($query);
		$stmt->bindParam(':school_id', $this->school_id);
		$stmt->execute();

		return $stmt;
	}
}


?>