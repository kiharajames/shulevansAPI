<?php
class AccountingPeriod{
	private $table = "accounting_periods";
	public $school;

	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function readPeriod(){
		$query = "SELECT * FROM ".$this->table." WHERE school=:school";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':school', $this->school);
		$stmt->execute();

		return $stmt;
	}

}

?>