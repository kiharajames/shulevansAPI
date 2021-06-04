<?php
class Parents{
	//db things
	private $conn;
	private $table = 'parents';

	//post properties
	public $phone;
	public $password;
	public $phone_check;
	public $password_check;
	public $first_name;
	public $last_name;
	public $gender;
	public $email;
	public $address;


	//constructor
	public function __construct($conn){
		$this->conn = $conn;
	}

	//getting posts from the database
	public function authenticate(){
		$query = "SELECT * FROM ".$this->table." WHERE phone=:phone AND login =:login";
		//prepare
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':phone', $this->phone);
		$stmt->bindParam(':login', $this->login);
		//execute
		$stmt->execute();
		
		
		return $stmt;
	}



	public function readParent(){
		$query = "SELECT * FROM ".$this->table." WHERE phone=:phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':phone', $this->phone);
		$stmt->execute();

		return $stmt;
	}

	public function getKids(){
		$query = "SELECT u.*, s.name as school_name FROM users as u,schools as s WHERE u.phone=:phone AND s.school_id=u.school";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':phone', $this->phone);
		$stmt->execute();

		return $stmt;
	}

	public function updateProfile(){
		$query = "UPDATE ".$this->table." SET first_name=:first_name, last_name=:last_name, gender=:gender, phone=:newPhone, email=:email WHERE phone=:phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':newPhone', $this->newPhone);
		$stmt->bindParam(':phone', $this->phone);
		$stmt->bindParam(':first_name', $this->first_name);
		$stmt->bindParam(':last_name', $this->last_name);
		$stmt->bindParam(':gender', $this->gender);
		$stmt->bindParam(':email', $this->email);
		$stmt->execute();

		return $stmt;
	}

	public function changePassword(){
		$query = "UPDATE ".$this->table." SET login=:login WHERE phone=:phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':login', $this->newPass);
		$stmt->bindParam(':phone', $this->phone);
		$stmt->execute();

		return $stmt;
	}

	public function updateLocation(){
		$query = "UPDATE ".$this->table." SET address=:address WHERE phone=:phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':address', $this->address);
		$stmt->bindParam(':phone', $this->phone);
		$stmt->execute();

		return $stmt;
	}

}

?>
