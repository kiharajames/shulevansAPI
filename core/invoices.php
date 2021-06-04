<?php
	class Invoices{
		private $table = "invoices";
		private $conn;
		public $adm_no;
		public $term;
		public $year;
		public $charges;
		public $date;
		public $invoice_date;
		public $invoice_time;

		public function __construct($conn){
			$this->conn = $conn;
		}

		public function read_kids_invoice(){
			$query = "SELECT * FROM ".$this->table." WHERE adm_no=:adm_no";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(':adm_no', $this->adm_no);

			$stmt->execute();

			return $stmt;
		}

	}
	

?>