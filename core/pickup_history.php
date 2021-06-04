<?php
Class PickupHistory{
	private $conn;
	private $table = "history";

	public $mybus;

	public function __construct($conn){
		$this->conn = $conn;
	} 

	public function getAllHistory(){
		$query = "SELECT h.*, u.first_name, u.last_name from ".$this->table." as h, users as u where bus =:mybus AND h.adm_no = u.adm_no ORDER BY id DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':mybus', $this->mybus);
		$stmt->execute();
		return $stmt;
	}

	public function getTallyPickupHome(){
		$query1 = "SELECT count(*) as count FROM history WHERE status = 'picked from home' AND day = CURRENT_DATE AND bus = :mybus";//those picked from home

		$stmt1 = $this->conn->prepare($query1);
		$stmt1->bindParam(':mybus', $this->mybus);
		$stmt1->execute();

		return($stmt1);

	}

	public function getTallyDroppedSchl(){

        $query2 = "SELECT count(*) as count FROM history WHERE status = 'dropped at school' AND day = CURRENT_DATE AND bus = :mybus";//those dropped at school

        $stmt2 = $this->conn->prepare($query2);
		$stmt2->bindParam(':mybus', $this->mybus);
		$stmt2->execute();

		return($stmt2);

	}

		public function getTallyDroppedHm(){

        $query3 = "SELECT count(*) as count FROM history WHERE status = 'dropped at home' AND day = CURRENT_DATE AND bus = :mybus";//those dropped at home

        $stmt3 = $this->conn->prepare($query3);
		$stmt3->bindParam(':mybus', $this->mybus);
		$stmt3->execute();

		return($stmt3);

	}

	public function getTallyPickedSchl(){

        $query4 = "SELECT count(*) as count FROM history WHERE status = 'left school' AND day = CURRENT_DATE AND bus = :mybus";//those picked from school

        $stmt4 = $this->conn->prepare($query4);
		$stmt4->bindParam(':mybus', $this->mybus);
		$stmt4->execute();

		return($stmt4);

	}

	public function getKidsHistory(){
		$query = "SELECT * from ".$this->table." where adm_no = :adm_no ORDER BY id DESC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':adm_no', $this->adm_no);
		$stmt->execute();
		return $stmt;
	}
}

?>