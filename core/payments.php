<?php
class Payments{
	public $payment_date;
	private $table = "payments";
	public $payment_time;
	public $amount;
	public $adm_no;
	public $txn_id;
	public $payment_gross;
	public $payment_status;
	public $school;
	public $term;
	public $year;

	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function readFinances(){
		$query = "SELECT * FROM ".$this->table." WHERE adm_no=:adm_no ORDER BY payment_id DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':adm_no', $this->adm_no);
		$stmt->execute();

		return $stmt;
	}

	public function recordPayment(){
		$query = "INSERT INTO ".$this->table." (payment_date, payment_time, adm_no, txn_id, payment_gross, payment_status, school, term, year) VALUES(:payment_date, :payment_time, :adm_no, :txn_id, :payment_gross, :payment_status, :school, :term, :year)";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":payment_date",$this->payment_date);
		$stmt->bindParam(":payment_time",$this->payment_time);
		$stmt->bindParam(":adm_no",$this->adm_no);
		$stmt->bindParam(":txn_id",$this->txn_id);
		$stmt->bindParam(":payment_gross",$this->payment_gross);
		$stmt->bindParam(":payment_status",$this->payment_status);
		$stmt->bindParam(":school",$this->school);
		$stmt->bindParam(":term",$this->term);
		$stmt->bindParam(":year",$this->year);
		
		$stmt->execute();

		return $stmt;
	}
}

?>