<?php
class Drivers{
	//db things

	private $conn;
	private $table = 'drivers';

	//driver properties
	public $phone;
	public $password;

	public $phone_check;
	public $password_check;

	public $first_name;
    public $last_name;
    public $gender;
    public $id;
    public $krapin;
    public $nssfnumber;
    public $nhifnumber;
    public $role;
    public $other_role;
    public $avatar;
    public $bus;
    public $acct_number;
    public $basic_salary;
    
    //variables for recording pickup after scan
    public $date_recorded;
    public $time_recorded;
    public $adm_no;
    public $pickuppoint;
    public $school;
	public $mode;


	//constructor
	public function __construct($conn){
		$this->conn = $conn;
	}

	//getting drivers data to authenticate the login credentials
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

	//read a single drivers record
	public function read_single(){
		$query = "SELECT * FROM ".$this->table." WHERE phone =:phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':phone', $this->phone);
		$stmt->execute();

		return $stmt;
	}

	public function readDriverByVehicle(){
		$query = "SELECT * FROM ".$this->table." WHERE bus =:bus";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':bus', $this->bus);
		$stmt->execute();

		return $stmt;
	}

	public function record_pickup(){
		$query = "insert INTO history(adm_no, day, time, bus, ppoint, status, school) values (:adm_no, :date_recorded, :time_recorded, :bus, :pickuppoint, :mode, :school)";
		$stmt = $this->conn->prepare($query);
		$this->adm_no = htmlspecialchars(strip_tags($this->adm_no));
		$this->date_recorded = htmlspecialchars(strip_tags($this->date_recorded));
		$this->time_recorded = htmlspecialchars(strip_tags($this->time_recorded));
		$this->bus = htmlspecialchars(strip_tags($this->bus));
		$this->pickuppoint = htmlspecialchars(strip_tags($this->pickuppoint));
		$this->mode = htmlspecialchars(strip_tags($this->mode));
		$this->school = htmlspecialchars(strip_tags($this->school));

		$stmt->bindParam(':adm_no', $this->adm_no);
		$stmt->bindParam(':date_recorded', $this->date_recorded);
		$stmt->bindParam(':time_recorded', $this->time_recorded);
		$stmt->bindParam(':bus', $this->bus);
		$stmt->bindParam(':pickuppoint', $this->pickuppoint);
		$stmt->bindParam(':mode', $this->mode);
		$stmt->bindParam(':school', $this->school);

		if ($stmt->execute()) {
			return true;
		}else{
			return false;
		}
	}

	//record the message to be sent in the database
	public function recordMessage(){
		$query = "insert INTO general_messaging(date_sent, time_sent, recipient, message_type, message, school, read_status) values (:date_sent, :time_sent, :recipient, :message_type, :message, :school, :read_status)";
		$stmt = $this->conn->prepare($query);
		$this->recipient = htmlspecialchars(strip_tags($this->adm_no));
		$this->date_sent = htmlspecialchars(strip_tags($this->date_recorded));
		$this->time_sent = htmlspecialchars(strip_tags($this->time_recorded));
		$this->message_type = "picking";
		$this->message = htmlspecialchars(strip_tags($this->message));
		$this->read_status = 0;
		$this->school = htmlspecialchars(strip_tags($this->school));

		$stmt->bindParam(':recipient', $this->recipient);
		$stmt->bindParam(':date_sent', $this->date_sent);
		$stmt->bindParam(':time_sent', $this->time_sent);
		$stmt->bindParam(':message_type', $this->message_type);
		$stmt->bindParam(':message', $this->message);
		$stmt->bindParam(':read_status', $this->read_status);
		$stmt->bindParam(':school', $this->school);

		if ($stmt->execute()) {
			return true;
		}else{
			return false;
		}
	}

	public function changePhone(){
		$query = "UPDATE ".$this->table." SET phone=:newPhone WHERE phone=:phone";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':newPhone', $this->newPhone);
		$stmt->bindParam(':phone', $this->phone);
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

}

?>
