<?php
class DriverTokens{
	public $id;
	public $phone;
	public $token;

	private $conn;
	private $table = "driver_tokens";

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function checkPhone(){
		//check if the number exists
		$query = "SELECT * FROM ".$this->table." WHERE phone = :phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':phone', $this->phone);
		$stmt->execute();

		return $stmt;
	}

	public function addToken(){
		$query = "INSERT INTO ".$this->table." (token, phone) VALUES(:token, :phone)";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':token', $this->token);
		$stmt->bindParam(':phone', $this->phone);
		$stmt->execute();

		return $stmt;
	}

	public function updateToken(){
		$query = "UPDATE ".$this->table." SET token = :token WHERE phone = :phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':token', $this->token);
		$stmt->bindParam(':phone', $this->phone);
		$stmt->execute();

		return $stmt;
	}

	public function readToken(){
		$query = "SELECT token FROM ".$this->table." WHERE phone =:phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':phone', $this->phone);
		$stmt->execute();

		return $stmt;

	}


}
?>