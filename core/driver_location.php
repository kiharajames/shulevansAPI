<?php
class DriverLocation{
	private $table = "driver_loc_realtime";
	public $driver_phone;
	private $conn;
	public $location;

	public function __construct($conn){
		$this->conn = $conn;
	}

	public function updateLocation(){
		$query = "UPDATE ".$this->table." SET location=:location WHERE driver_phone=:driver_phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':location', $this->location);
		$stmt->bindParam(':driver_phone', $this->driver_phone);

		$stmt->execute();

		return $stmt;
	}

	public function readLocation(){
		$query = "SELECT * FROM ".$this->table." WHERE driver_phone = :driver_phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':driver_phone', $this->driver_phone);

		$stmt->execute();

		return $stmt;
	}

	public function addLocation(){
		$query = "INSERT INTO ".$this->table." (driver_phone, location) VALUES(:driver_phone, :location)";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':location', $this->location);
		$stmt->bindParam(':driver_phone', $this->driver_phone);

		$stmt->execute();

		return $stmt;
	}

	public function checkIfDriverExists(){
		$query = "SELECT * FROM ".$this->table." WHERE driver_phone = :driver_phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':driver_phone', $this->driver_phone);

		$stmt->execute();

		return $stmt;
	}
}
?>