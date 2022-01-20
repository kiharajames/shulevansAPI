<?php
Class Student{
	private $conn;
	private $table = 'users';

	public $first_name;
	public $last_name;
	public $gender;
	public $address;
	public $email;
	public $phone;
	public $avatar;
	public $pickup;
	public $class;
	public $dob;
	public $adm_no;
	public $route;
	public $school;
	public $termly_fees;
	public $qrcodetxt;
	public $bus;
	public $pickuppoint;
	public $pickuppointid;
	public $balance;
	public $pickup_vehicle;
	public $dropping_vehicle;
	public $pickup_vehicle_trip;
	public $dropping_vehicle_trip;
	public $alt_phone;

	//constructor
	public function __construct($conn){
		$this->conn = $conn;
	}

	//read data a single kid
	public function readSingle(){
		$query = "SELECT u.*, s.name as school_name FROM ".$this->table." as u,schools as s WHERE u.adm_no=:adm_no AND s.school_id=u.school";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':adm_no', $this->adm_no);
		$stmt->execute();
		return $stmt;
	}

	public function readRouteData(){
		$query = "SELECT u.*, s.name as school_name, r.route_name as route_name, p.point_name as pickuppoint_name FROM ".$this->table." as u,schools as s, routes as r, pickup_points as p WHERE u.adm_no=:adm_no AND s.school_id=u.school AND u.pickuppointid = p.point_id AND r.route_id = u.route_id";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':adm_no', $this->adm_no);
		$stmt->execute();
		return $stmt;
	}

	public function updateProfile(){
		$query = "UPDATE ".$this->table." SET first_name=:first_name, last_name=:last_name, gender=:gender, class=:class WHERE adm_no=:adm_no";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':adm_no', $this->adm_no);
		$stmt->bindParam(':first_name', $this->first_name);
		$stmt->bindParam(':last_name', $this->last_name);
		$stmt->bindParam(':gender', $this->gender);
		$stmt->bindParam(':class', $this->class);
		$stmt->execute();

		return $stmt;
	}

	public function updateBalance(){
		$query = "UPDATE ".$this->table." SET balance=balance-:payment_gross WHERE adm_no=:adm_no AND school=:school";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':payment_gross', $this->payment_gross);
		$stmt->bindParam(':adm_no', $this->adm_no);
		$stmt->bindParam(':school', $this->school);

		$stmt->execute();

		return $stmt;
	}

	public function updateLocation(){
		$query = "UPDATE ".$this->table." SET address=:address WHERE adm_no=:adm_no";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':address', $this->address);
		$stmt->bindParam(':adm_no', $this->adm_no);

		$stmt->execute();
		return $stmt;
	}

	public function selectKidsByBus(){
		$query = "SELECT u.*, s.name as school_name FROM ".$this->table." as u,schools as s WHERE (u.pickup_vehicle=:bus) or (u.dropping_vehicle=:bus2) GROUP BY u.adm_no";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':bus', $this->bus);
		$stmt->bindParam(':bus2', $this->bus);
		$stmt->execute();
		return $stmt;
	}

}

?>