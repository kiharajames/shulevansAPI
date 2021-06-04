<?php
class PasswordEdits{
	//db things
	private $conn;
	private $table = 'password_edits';

	//post properties
	public $phone;
	public $number = "1";


	//constructor
	public function __construct($conn){
		$this->conn = $conn;
	}

	public function updatePass(){
		$query = "UPDATE ".$this->table." SET edited=:number WHERE phone=:phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':phone', $this->phone);
		$stmt->bindParam(':number', $this->number);
		$stmt->execute();
		
		return $stmt;
	}


}

?>
